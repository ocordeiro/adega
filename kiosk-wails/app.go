package main

import (
	"bytes"
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

// ReportBeverage reports a barcode as a beverage (wine or spirit)
func (a *App) ReportBeverage(barcode string, beverageType string) bool {
	url := fmt.Sprintf("%s/api/v1/bebida/reportar", apiBaseURL)
	payload, _ := json.Marshal(map[string]string{
		"barcode": barcode,
		"type":    beverageType,
	})

	req, err := http.NewRequest("POST", url, bytes.NewReader(payload))
	if err != nil {
		return false
	}
	req.Header.Set("Accept", "application/json")
	req.Header.Set("Content-Type", "application/json")
	req.Header.Set("Authorization", "Bearer "+apiToken)

	resp, err := a.client.Do(req)
	if err != nil {
		return false
	}
	defer resp.Body.Close()

	return resp.StatusCode == 200
}

// SettingsResult holds the kiosk appearance settings
type SettingsResult struct {
	LogoURL         *string `json:"logo_url"`
	ColorPrimary    string  `json:"color_primary"`
	ColorSecondary  string  `json:"color_secondary"`
	ColorBackground string  `json:"color_background"`
	ColorText       string  `json:"color_text"`
	ElementScale    float64 `json:"element_scale"`
	FontScale       float64 `json:"font_scale"`
}

// FetchAds returns a list of active ad video URLs
func (a *App) FetchAds() []string {
	url := fmt.Sprintf("%s/api/v1/anuncios", apiBaseURL)
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return nil
	}
	req.Header.Set("Accept", "application/json")
	req.Header.Set("Authorization", "Bearer "+apiToken)

	resp, err := a.client.Do(req)
	if err != nil {
		return nil
	}
	defer resp.Body.Close()

	if resp.StatusCode != 200 {
		return nil
	}

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return nil
	}

	var result struct {
		Data []struct {
			VideoURL string `json:"video_url"`
		} `json:"data"`
	}
	if err := json.Unmarshal(body, &result); err != nil {
		return nil
	}

	var urls []string
	for _, ad := range result.Data {
		if ad.VideoURL != "" {
			urls = append(urls, ad.VideoURL)
		}
	}
	return urls
}

// FetchSettings returns the kiosk appearance settings from the API
func (a *App) FetchSettings() *SettingsResult {
	url := fmt.Sprintf("%s/api/v1/configuracoes", apiBaseURL)
	req, err := http.NewRequest("GET", url, nil)
	if err != nil {
		return nil
	}
	req.Header.Set("Accept", "application/json")
	req.Header.Set("Authorization", "Bearer "+apiToken)

	resp, err := a.client.Do(req)
	if err != nil {
		return nil
	}
	defer resp.Body.Close()

	if resp.StatusCode != 200 {
		return nil
	}

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		return nil
	}

	var result struct {
		Data SettingsResult `json:"data"`
	}
	if err := json.Unmarshal(body, &result); err != nil {
		return nil
	}

	return &result.Data
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
