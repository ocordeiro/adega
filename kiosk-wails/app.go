package main

import (
	"context"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"time"
)

const (
	apiBaseURL = "https://adega.ocongresso.ai"
	apiToken   = "022fb27d031b5b926b72e05ebd2f74850894999160e93d490b34896f9d7b8ea6"
)

type App struct {
	ctx    context.Context
	client *http.Client
}

func NewApp() *App {
	return &App{
		client: &http.Client{Timeout: 10 * time.Second},
	}
}

func (a *App) startup(ctx context.Context) {
	a.ctx = ctx
}

// BeverageResult is returned to the frontend
type BeverageResult struct {
	Success bool        `json:"success"`
	Data    interface{} `json:"data"`
	Error   string      `json:"error,omitempty"`
}

// LookupBarcode fetches a beverage by barcode from the API
func (a *App) LookupBarcode(barcode string) BeverageResult {
	url := fmt.Sprintf("%s/api/v1/bebida/%s", apiBaseURL, barcode)
	return a.fetchBeverage(url)
}

// RandomBeverage fetches a random beverage from the API
func (a *App) RandomBeverage() BeverageResult {
	url := fmt.Sprintf("%s/api/v1/bebida/aleatorio", apiBaseURL)
	return a.fetchBeverage(url)
}

func (a *App) fetchBeverage(url string) BeverageResult {
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return BeverageResult{Success: false, Error: "Erro ao criar requisição"}
	}

	req.Header.Set("Accept", "application/json")
	req.Header.Set("Authorization", "Bearer "+apiToken)

	resp, err := a.client.Do(req)
	if err != nil {
		return BeverageResult{Success: false, Error: "Erro de conexão com o servidor"}
	}
	defer resp.Body.Close()

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return BeverageResult{Success: false, Error: "Erro ao ler resposta"}
	}

	if resp.StatusCode == 404 {
		return BeverageResult{Success: false, Error: "Produto não encontrado"}
	}

	if resp.StatusCode != 200 {
		return BeverageResult{Success: false, Error: fmt.Sprintf("Erro na API: %d", resp.StatusCode)}
	}

	var apiResponse struct {
		Data interface{} `json:"data"`
	}
	if err := json.Unmarshal(body, &apiResponse); err != nil {
		return BeverageResult{Success: false, Error: "Erro ao processar resposta"}
	}

	return BeverageResult{Success: true, Data: apiResponse.Data}
}
