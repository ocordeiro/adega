import './style.css';
import { LookupBarcode, RandomBeverage, FetchAds } from '../wailsjs/go/main/App';

// ── State ──
let currentView = 'scanner'; // 'scanner' | 'detail'
let barcodeBuffer = '';
let barcodeTimer = null;
let adUrls = [];
let adTimer = null;

const AD_DELAY = 30000; // 30s inactivity before showing ads

// ── Helpers ──
function esc(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function getPhoto(data) {
    if (data.photos && data.photos.length) {
        return data.photos[0].card || data.photos[0].original || null;
    }
    return null;
}

function getOrigin(data) {
    if (!data.country) return '';
    let origin = data.country.name;
    if (data.region) origin += ', ' + data.region.name;
    return origin;
}

function starsHTML(rating) {
    if (!rating) return '';
    const r = Math.round(rating);
    let s = '<div class="stars">';
    for (let i = 1; i <= 5; i++) s += `<span class="star ${i <= r ? '' : 'empty'}">★</span>`;
    s += '</div>';
    return s;
}

// Select up to 3 items with distinct first-occasion
function selectPreviewWithDistinctOccasions(items) {
    if (!items || !items.length) return [];
    const result = [];
    const seenOcc = new Set();
    const shuffled = [...items].sort(() => Math.random() - 0.5);
    for (const item of shuffled) {
        if (result.length >= 3) break;
        const occ = item.occasions && item.occasions.length ? item.occasions[0] : null;
        const occId = occ ? occ.id : null;
        if (occId && seenOcc.has(occId)) continue;
        if (occId) seenOcc.add(occId);
        result.push(item);
    }
    return result;
}

// ── Flash ──
function showFlash(msg) {
    const el = document.getElementById('flash');
    if (!el) return;
    el.textContent = msg;
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 4000);
}

// ── Loading ──
function showLoading() {
    const ov = document.createElement('div');
    ov.className = 'loading-overlay';
    ov.id = 'loading';
    ov.innerHTML = '<div class="loading-spinner"></div>';
    document.body.appendChild(ov);
}
function hideLoading() {
    const el = document.getElementById('loading');
    if (el) el.remove();
}

// ═══════════════════════════════════════════════
// SCANNER HOME SCREEN
// ═══════════════════════════════════════════════
function showScanner() {
    currentView = 'scanner';
    document.getElementById('app').innerHTML = `
<div class="bg"></div>
<div class="page">
    <div class="logo">Adega</div>
    <div class="logo-sub">Sommelier Digital</div>
    <div class="rule"></div>
    <div class="scanner-wrap">
        <div class="scanner-glow"></div>
        <div class="scanner-frame">
            <div class="corner-bl"></div>
            <div class="corner-br"></div>
            <div class="scan-line"></div>
            <div class="scanner-inner">
                <svg class="barcode-icon" width="88" height="64" viewBox="0 0 88 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0"  y="0"  width="4"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="8"  y="0"  width="2"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="13" y="0"  width="6"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="23" y="0"  width="2"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="28" y="0"  width="4"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="36" y="0"  width="2"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="42" y="0"  width="6"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="52" y="0"  width="2"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="57" y="0"  width="4"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="65" y="0"  width="2"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="70" y="0"  width="6"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="80" y="0"  width="2"  height="64" rx="1" fill="#1a1a2e"/>
                    <rect x="84" y="0"  width="4"  height="64" rx="1" fill="#1a1a2e"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="instruction">
        <p class="instruction-main">Aproxime a garrafa do leitor</p>
        <p class="instruction-sub">para descobrir harmonizações e receitas</p>
    </div>
    <button class="demo-btn" id="randomBtn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        Ver uma bebida aleatória
    </button>
</div>
<div class="flash" id="flash"></div>
<div class="footer">Aproxime o código de barras</div>
${adUrls.length ? `
<div id="ad-overlay" class="ad-overlay hidden">
    <video id="ad-video" class="ad-video" muted playsinline></video>
    <div class="ad-hint">Toque para continuar</div>
</div>` : ''}
`;
    document.getElementById('randomBtn').addEventListener('click', fetchRandom);
    initAdSystem();
}

// ═══════════════════════════════════════════════
// AD SYSTEM
// ═══════════════════════════════════════════════
function initAdSystem() {
    if (!adUrls.length) return;
    const overlay = document.getElementById('ad-overlay');
    const video = document.getElementById('ad-video');
    if (!overlay || !video) return;

    let adIndex = 0;

    function playAd(idx) {
        video.src = adUrls[idx];
        video.load();
        video.play().catch(() => {});
    }

    video.addEventListener('ended', () => {
        adIndex = (adIndex + 1) % adUrls.length;
        playAd(adIndex);
    });

    function showAds() {
        if (currentView !== 'scanner') return;
        adIndex = 0;
        playAd(0);
        overlay.classList.remove('hidden');
    }

    function dismissAds() {
        overlay.classList.add('hidden');
        video.pause();
        video.src = '';
        scheduleAds();
    }

    function scheduleAds() {
        clearTimeout(adTimer);
        adTimer = setTimeout(showAds, AD_DELAY);
    }

    overlay.addEventListener('click', dismissAds);
    overlay.addEventListener('touchend', e => { e.preventDefault(); dismissAds(); });
    document.addEventListener('keydown', () => {
        if (overlay && !overlay.classList.contains('hidden')) dismissAds();
    });
    document.addEventListener('pointerdown', e => {
        if (!overlay.contains(e.target)) scheduleAds();
    });

    scheduleAds();
}

// ── Fetch beverage ──
async function fetchRandom() {
    showLoading();
    try {
        const result = await RandomBeverage();
        hideLoading();
        if (result.success) {
            renderBeverage(result.data);
        } else {
            showFlash(result.error || 'Erro ao buscar bebida');
        }
    } catch (e) {
        hideLoading();
        showFlash('Erro de conexão');
    }
}

async function fetchByBarcode(barcode) {
    showLoading();
    try {
        const result = await LookupBarcode(barcode);
        hideLoading();
        if (result.success) {
            renderBeverage(result.data);
        } else {
            showFlash(result.error || 'Produto não encontrado');
        }
    } catch (e) {
        hideLoading();
        showFlash('Erro de conexão');
    }
}

function renderBeverage(data) {
    clearTimeout(adTimer);
    if (data.type === 'spirit') {
        renderSpirit(data);
    } else {
        renderWine(data);
    }
}

// ═══════════════════════════════════════════════
// WINE
// ═══════════════════════════════════════════════
function renderWine(w) {
    currentView = 'detail';
    const photo = getPhoto(w);
    const hasRecipes = w.recipes && w.recipes.length;
    const hasFoods = w.foods && w.foods.length;
    const previewFoods = selectPreviewWithDistinctOccasions(w.foods);
    const slideRecipes = hasRecipes ? w.recipes.slice(0, 3) : [];
    const TOTAL = 2;

    // Photo
    let photoHTML;
    if (photo) {
        photoHTML = `<img class="wine-photo" src="${esc(photo)}" alt="${esc(w.name)}" draggable="false">`;
    } else {
        photoHTML = `<div class="wine-photo-placeholder">
            <svg width="36" height="64" viewBox="0 0 90 160" fill="none" opacity=".35">
                <rect x="36" y="4" width="18" height="22" rx="4" fill="#d93f35"/>
                <path d="M28 36 Q20 50 18 70 L18 138 Q18 148 28 150 L62 150 Q72 148 72 138 L72 70 Q70 50 62 36 Z" fill="#d93f35"/>
            </svg>
        </div>`;
    }

    // Meta items
    let metaItems = '';
    if (w.producer) metaItems += `<div class="meta-item"><span class="meta-label">Produtor</span><span class="meta-value">${esc(w.producer.name)}</span></div>`;
    const origin = getOrigin(w);
    if (origin) metaItems += `<div class="meta-item"><span class="meta-label">Origem</span><span class="meta-value">${esc(origin)}</span></div>`;
    if (w.alcohol_content) metaItems += `<div class="meta-item"><span class="meta-label">Álcool</span><span class="meta-value">${Number(w.alcohol_content).toFixed(1)}%</span></div>`;
    if (w.serving_temp_min || w.serving_temp_max) {
        let temp = '';
        if (w.serving_temp_min && w.serving_temp_max) temp = `${w.serving_temp_min}°C–${w.serving_temp_max}°C`;
        else if (w.serving_temp_min) temp = `${w.serving_temp_min}°C`;
        else temp = `${w.serving_temp_max}°C`;
        metaItems += `<div class="meta-item"><span class="meta-label">Temperatura</span><span class="meta-value">${temp}</span></div>`;
    }
    const hasMeta = metaItems !== '';

    // Grapes
    let grapesHTML = '';
    if (w.grape_varieties && w.grape_varieties.length) {
        grapesHTML = '<div class="grape-tags">' + w.grape_varieties.map(g =>
            `<span class="grape-tag">${esc(g.name)}${g.percentage ? ' ' + g.percentage + '%' : ''}</span>`
        ).join('') + '</div>';
    }

    // Preview foods (up to 3 with distinct occasions)
    let previewHTML = '';
    if (previewFoods.length) {
        previewHTML = `<div class="harmony-section">
            <div class="harmony-divider"></div>
            <p class="harmony-title">Combina com</p>
            <div class="pairing-grid">
                ${previewFoods.map(f => {
                    const occ = f.occasions && f.occasions.length ? f.occasions[0] : null;
                    const img = f.image
                        ? `<img src="${esc(f.image)}" alt="${esc(f.name)}" draggable="false">`
                        : `<div class="pairing-img-ph">🍽️</div>`;
                    return `<div class="pairing-col">
                        ${occ ? `<p class="pairing-occ-label">${esc(occ.icon)} ${esc(occ.name)}</p>` : ''}
                        <div class="pairing-img-wrap">${img}</div>
                        <p class="pairing-food-name">${esc(f.name)}</p>
                        ${f.category ? `<p class="pairing-food-cat">${esc(f.category)}</p>` : ''}
                    </div>`;
                }).join('')}
            </div>
        </div>`;
    }

    // Slide 2: Recipes
    let recipesSlideHTML = '';
    if (slideRecipes.length) {
        recipesSlideHTML = `<div>
            <div class="recipes-grid">
                ${slideRecipes.map(r => {
                    const img = r.photo
                        ? `<img class="recipe-img" draggable="false" src="${esc(r.photo)}" alt="${esc(r.name)}">`
                        : `<div class="recipe-ph">👨‍🍳</div>`;
                    let ingredientsHTML = '';
                    if (r.ingredients && r.ingredients.length) {
                        ingredientsHTML = `<div class="recipe-ingredients"><p class="recipe-ingredients-title">Ingredientes</p>` +
                            r.ingredients.map(ing => `<div class="recipe-ingredient"><span class="recipe-ingredient-name">${esc(ing.name)}</span><span class="recipe-ingredient-qty">${ing.quantity ? esc(String(ing.quantity)) : ''}${ing.unit ? ' ' + esc(ing.unit) : ''}</span></div>`).join('') +
                            '</div>';
                    }
                    return `<div class="recipe-card">${img}<div class="recipe-body">
                        <div class="recipe-tags">
                            <span class="recipe-tag difficulty">${esc(r.difficulty ? r.difficulty.charAt(0).toUpperCase() + r.difficulty.slice(1) : '')}</span>
                            ${r.prep_time ? `<span class="recipe-tag time">${r.prep_time} min</span>` : ''}
                        </div>
                        <h3 class="recipe-name">${esc(r.name)}</h3>
                        ${r.description ? `<p class="recipe-desc">${esc(r.description)}</p>` : ''}
                        ${r.notes ? `<div class="recipe-note">${esc(r.notes)}</div>` : ''}
                        ${ingredientsHTML}
                        ${r.instructions ? `<div class="recipe-steps">${esc(r.instructions)}</div>` : ''}
                    </div></div>`;
                }).join('')}
            </div>
        </div>`;
    } else {
        recipesSlideHTML = `<div class="empty-state"><p style="font-size:2rem;margin-bottom:.7rem">👨‍🍳</p><p>Receitas ainda não cadastradas.</p></div>`;
    }

    const hasSlide2 = hasFoods || hasRecipes;

    document.getElementById('app').innerHTML = `
<button class="btn-back-fixed" id="btnBack" title="Voltar">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
</button>

<div class="track-wrap"><div class="track" id="track">

<div class="slide slide-wine"><div class="wine-slide-inner">
<div class="wine-layout">
    <div class="wine-photo-col">
        <div class="wine-photo-frame">${photoHTML}</div>
        ${starsHTML(w.rating)}
    </div>
    <div class="wine-info-col">
        ${w.wine_type ? `<div class="wine-badge"><svg width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg> ${esc(w.wine_type.name)}</div>` : ''}
        <h1 class="wine-name">${esc(w.name)}</h1>
        ${w.vintage ? `<p class="wine-vintage">${esc(w.vintage)}</p>` : ''}
        ${w.description ? `<p class="wine-desc">${esc(w.description)}</p>` : ''}
        ${hasMeta ? `<div class="wine-meta-row">${metaItems}</div>` : ''}
        ${grapesHTML}
        ${previewHTML}
        ${hasSlide2 ? `<button class="btn-ver-mais" id="btn-ver-mais">Ver mais <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>` : ''}
    </div>
</div>
</div></div>

<div class="slide slide-pairings">
    <div class="section-header">
        <p class="section-label">Receitas</p>
        <h2 class="section-title">Receitas que combinam com este vinho</h2>
        <p class="section-sub">${esc(w.name)}${w.vintage ? ' · ' + esc(w.vintage) : ''}</p>
    </div>
    <div class="pairings-body">
        ${recipesSlideHTML}
    </div>
</div>

</div></div>`;

    initDetailCarousel(TOTAL);
}

// ═══════════════════════════════════════════════
// SPIRIT
// ═══════════════════════════════════════════════
function renderSpirit(s) {
    currentView = 'detail';
    const sPhoto = getPhoto(s);
    const hasDrinks = s.drink_recipes && s.drink_recipes.length;
    const previewDrinks = selectPreviewWithDistinctOccasions(s.drink_recipes);
    const slideDrinks = hasDrinks ? s.drink_recipes.slice(0, 3) : [];
    const TOTAL = 2;

    // Photo
    let photoHTML;
    if (sPhoto) {
        photoHTML = `<img class="spirit-photo" src="${esc(sPhoto)}" alt="${esc(s.name)}" draggable="false">`;
    } else {
        photoHTML = `<div class="spirit-photo-placeholder">
            <svg width="48" height="64" viewBox="0 0 48 64" fill="none" opacity=".35">
                <rect x="18" y="2" width="12" height="10" rx="2" fill="#d93f35"/>
                <path d="M14 16 Q10 24 10 34 L10 54 Q10 60 16 62 L32 62 Q38 60 38 54 L38 34 Q38 24 34 16 Z" fill="#d93f35"/>
            </svg>
        </div>`;
    }

    // Meta items
    let metaItems = '';
    if (s.producer) metaItems += `<div class="meta-item"><span class="meta-label">Produtor</span><span class="meta-value">${esc(s.producer.name)}</span></div>`;
    if (s.country) metaItems += `<div class="meta-item"><span class="meta-label">Origem</span><span class="meta-value">${esc(s.country.name)}</span></div>`;
    if (s.alcohol_content) metaItems += `<div class="meta-item"><span class="meta-label">Álcool</span><span class="meta-value">${Number(s.alcohol_content).toFixed(1)}%</span></div>`;
    const hasMeta = metaItems !== '';

    // Preview drinks (up to 3 with distinct occasions)
    let previewHTML = '';
    if (previewDrinks.length) {
        previewHTML = `<div class="harmony-section">
            <div class="harmony-divider"></div>
            <p class="harmony-title">Drinks que combinam</p>
            <div class="pairing-grid">
                ${previewDrinks.map(d => {
                    const occ = d.occasions && d.occasions.length ? d.occasions[0] : null;
                    const img = d.photo
                        ? `<img src="${esc(d.photo)}" alt="${esc(d.name)}" draggable="false">`
                        : `<div class="pairing-img-ph">🍸</div>`;
                    return `<div class="pairing-col">
                        ${occ ? `<p class="pairing-occ-label">${esc(occ.icon)} ${esc(occ.name)}</p>` : ''}
                        <div class="pairing-img-wrap">${img}</div>
                        <p class="pairing-food-name">${esc(d.name)}</p>
                        ${d.difficulty ? `<p class="pairing-food-cat">${esc(d.difficulty.charAt(0).toUpperCase() + d.difficulty.slice(1))}</p>` : ''}
                    </div>`;
                }).join('')}
            </div>
        </div>`;
    }

    // Slide 2: Drink cards
    let drinksSlideHTML = '';
    if (slideDrinks.length) {
        drinksSlideHTML = `<div>
            <p class="sub-label">Receitas de drinks</p>
            <div class="drinks-grid">
                ${slideDrinks.map(d => {
                    const img = d.photo
                        ? `<img class="drink-img" draggable="false" src="${esc(d.photo)}" alt="${esc(d.name)}">`
                        : `<div class="drink-ph">🍸</div>`;
                    let ingredientsHTML = '';
                    if (d.ingredients && d.ingredients.length) {
                        ingredientsHTML = `<div class="drink-ingredients"><p class="drink-ingredients-title">Ingredientes</p>` +
                            d.ingredients.map(ing => `<div class="drink-ingredient"><span class="drink-ingredient-name">${esc(ing.name)}</span><span class="drink-ingredient-qty">${ing.quantity ? esc(String(ing.quantity)) : ''}${ing.unit ? ' ' + esc(ing.unit) : ''}</span></div>`).join('') +
                            '</div>';
                    }
                    return `<div class="drink-card">${img}<div class="drink-body">
                        <div class="drink-tags">
                            <span class="drink-tag difficulty">${esc(d.difficulty ? d.difficulty.charAt(0).toUpperCase() + d.difficulty.slice(1) : '')}</span>
                            ${d.prep_time ? `<span class="drink-tag time">${d.prep_time} min</span>` : ''}
                        </div>
                        <h3 class="drink-name">${esc(d.name)}</h3>
                        ${d.description ? `<p class="drink-desc">${esc(d.description)}</p>` : ''}
                        ${ingredientsHTML}
                        ${d.instructions ? `<div class="drink-steps">${esc(d.instructions)}</div>` : ''}
                    </div></div>`;
                }).join('')}
            </div>
        </div>`;
    } else {
        drinksSlideHTML = `<div class="empty-state"><p style="font-size:2rem;margin-bottom:.7rem">🍸</p><p>Receitas de drinks ainda não cadastradas.</p></div>`;
    }

    document.getElementById('app').innerHTML = `
<button class="btn-back-fixed" id="btnBack" title="Voltar">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
</button>

<div class="track-wrap"><div class="track" id="track">

<div class="slide slide-spirit"><div class="spirit-slide-inner">
<div class="spirit-layout">
    <div class="spirit-photo-col">
        <div class="spirit-photo-frame">${photoHTML}</div>
    </div>
    <div class="spirit-info-col">
        ${s.spirit_type ? `<div class="spirit-badge"><svg width="6" height="6" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg> ${esc(s.spirit_type.name)}</div>` : ''}
        <h1 class="spirit-name">${esc(s.name)}</h1>
        ${s.description ? `<p class="spirit-desc">${esc(s.description)}</p>` : ''}
        ${hasMeta ? `<div class="spirit-meta-row">${metaItems}</div>` : ''}
        ${previewHTML}
        ${hasDrinks ? `<button class="btn-ver-mais" id="btn-ver-mais">Ver mais <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg></button>` : ''}
    </div>
</div>
</div></div>

<div class="slide slide-drinks">
    <div class="section-header">
        <p class="section-label">Drinks</p>
        <h2 class="section-title">O que preparar com ${esc(s.name)}?</h2>
        <p class="section-sub">Receitas de coquetéis e drinks</p>
    </div>
    <div class="drinks-body">
        ${drinksSlideHTML}
    </div>
</div>

</div></div>`;

    initDetailCarousel(TOTAL);
}

// ═══════════════════════════════════════════════
// DETAIL CAROUSEL (shared by wine & spirit)
// ═══════════════════════════════════════════════
function initDetailCarousel(TOTAL) {
    const INACTIVITY_HOME = 60000;
    const INACTIVITY_READ = 180000;
    const DUR  = '380ms';
    const EASE = 'cubic-bezier(.25,.46,.45,.94)';

    let current = 0;
    let inactTimer;

    const track = document.getElementById('track');

    function goTo(idx) {
        if (idx < 0 || idx >= TOTAL) return;
        current = idx;
        track.style.transition = `transform ${DUR} ${EASE}`;
        track.style.transform  = `translateX(${-idx * 100}vw)`;
        resetInactivity();
        if (idx === 1) fitCards();
    }

    // "Ver mais" button
    const btnVerMais = document.getElementById('btn-ver-mais');
    if (btnVerMais) {
        btnVerMais.addEventListener('click', () => goTo(1));
        btnVerMais.addEventListener('touchend', e => { e.preventDefault(); goTo(1); });
    }

    // Keyboard
    const keyHandler = e => {
        if (currentView !== 'detail') return;
        if (e.key === 'ArrowRight') goTo(current + 1);
        if (e.key === 'ArrowLeft')  goTo(current - 1);
    };
    document.addEventListener('keydown', keyHandler);

    // Back button
    const btnBack = document.getElementById('btnBack');
    let _backTouched = false;
    btnBack.addEventListener('touchend', e => {
        e.preventDefault();
        _backTouched = true;
        setTimeout(() => _backTouched = false, 300);
        if (current > 0) goTo(current - 1); else { clearTimeout(inactTimer); showScanner(); }
    });
    btnBack.addEventListener('click', e => {
        e.preventDefault();
        if (_backTouched) return;
        if (current > 0) goTo(current - 1); else { clearTimeout(inactTimer); showScanner(); }
    });

    function resetInactivity() {
        clearTimeout(inactTimer);
        const delay = current === 0 ? INACTIVITY_HOME : INACTIVITY_READ;
        inactTimer = setTimeout(() => showScanner(), delay);
    }
    document.addEventListener('pointerdown', resetInactivity);

    goTo(0);

    // Dynamic font sizing for recipe/drink cards
    function fitCards() {
        const cardSelector = document.querySelector('.recipe-card') ? '.recipe-card' : '.drink-card';
        const bodySelector = document.querySelector('.recipe-card') ? '.recipe-body' : '.drink-body';
        const cards = Array.from(document.querySelectorAll(cardSelector));
        if (!cards.length) return;
        let minFs = Infinity;
        cards.forEach(card => {
            const body = card.querySelector(bodySelector);
            if (!body) return;
            card.style.fontSize = '';
            let lo = 8, hi = 52;
            card.style.fontSize = hi + 'px';
            for (let i = 0; i < 14; i++) {
                const mid = (lo + hi) / 2;
                card.style.fontSize = mid + 'px';
                body.scrollHeight <= body.clientHeight ? (lo = mid) : (hi = mid);
            }
            minFs = Math.min(minFs, Math.floor(lo));
        });
        cards.forEach(card => card.style.fontSize = minFs + 'px');
    }
    requestAnimationFrame(fitCards);
    let _rrt;
    window.addEventListener('resize', () => { clearTimeout(_rrt); _rrt = setTimeout(fitCards, 120); });
}

// ── Barcode scanner input (USB barcode readers send keystrokes) ──
document.addEventListener('keydown', e => {
    if (currentView !== 'scanner') return;
    // Dismiss ads on any key
    const overlay = document.getElementById('ad-overlay');
    if (overlay && !overlay.classList.contains('hidden')) return;

    if (e.key === 'Enter' && barcodeBuffer.length >= 4) {
        const code = barcodeBuffer;
        barcodeBuffer = '';
        clearTimeout(barcodeTimer);
        fetchByBarcode(code);
        return;
    }
    if (/^[0-9]$/.test(e.key)) {
        barcodeBuffer += e.key;
        clearTimeout(barcodeTimer);
        barcodeTimer = setTimeout(() => { barcodeBuffer = ''; }, 300);
    }
});

// ── Init ──
async function init() {
    // Load ads in background
    try {
        const urls = await FetchAds();
        if (urls && urls.length) {
            adUrls = urls;
        }
    } catch (e) {
        // Ads are optional, continue without them
    }
    showScanner();
}

init();
