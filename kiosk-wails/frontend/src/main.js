import './style.css';
import { LookupBarcode, RandomBeverage } from '../wailsjs/go/main/App';

// ── State ──
let currentView = 'scanner'; // 'scanner' | 'detail'
let barcodeBuffer = '';
let barcodeTimer = null;

// ── Render scanner home ──
function showScanner() {
    currentView = 'scanner';
    const hint = document.getElementById('swipeHint');
    if (hint) hint.remove();
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
                    <rect x="0"  y="0"  width="4"  height="64" rx="1" fill="white"/>
                    <rect x="8"  y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="13" y="0"  width="6"  height="64" rx="1" fill="white"/>
                    <rect x="23" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="28" y="0"  width="4"  height="64" rx="1" fill="white"/>
                    <rect x="36" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="42" y="0"  width="6"  height="64" rx="1" fill="white"/>
                    <rect x="52" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="57" y="0"  width="4"  height="64" rx="1" fill="white"/>
                    <rect x="65" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="70" y="0"  width="6"  height="64" rx="1" fill="white"/>
                    <rect x="80" y="0"  width="2"  height="64" rx="1" fill="white"/>
                    <rect x="84" y="0"  width="4"  height="64" rx="1" fill="white"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="instruction">
        <p class="instruction-main">Aproxime a garrafa do leitor</p>
        <p class="instruction-sub">para descobrir harmonizações e receitas</p>
    </div>
    <button class="demo-btn" id="randomBtn">
        <span class="demo-btn-icon">🍸</span>
        <span class="demo-btn-text">Ver uma bebida aleatória</span>
    </button>
</div>
<div class="flash" id="flash"></div>
<div class="footer">Aproxime o código de barras &bull; Deslize para explorar</div>
`;
    document.getElementById('randomBtn').addEventListener('click', fetchRandom);
}

function showFlash(msg) {
    const el = document.getElementById('flash');
    if (!el) return;
    el.textContent = msg;
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 4000);
}

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

// ── Render beverage ──
function renderBeverage(data) {
    if (data.type === 'spirit') {
        renderSpirit(data);
    } else {
        renderWine(data);
    }
}

// ── Helpers ──
function starsHTML(rating, extraClass = '') {
    if (!rating) return '';
    const r = Math.round(rating);
    let s = `<div class="stars ${extraClass}">`;
    for (let i = 1; i <= 5; i++) s += `<span class="star ${i <= r ? '' : 'empty'}">★</span>`;
    s += '</div>';
    return s;
}

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

// ════════════════════════════════════════════
// WINE
// ════════════════════════════════════════════
function renderWine(w) {
    currentView = 'detail';
    const hasOccasions = w.occasions && w.occasions.length;
    const hasFoods = w.foods && w.foods.length;
    const hasRecipes = w.recipes && w.recipes.length;
    const TOTAL = hasRecipes ? 3 : 2;
    const photo = getPhoto(w);

    // Photo
    let photoHTML;
    if (photo) {
        photoHTML = `<img class="wine-photo" src="${esc(photo)}" alt="${esc(w.name)}" draggable="false" style="aspect-ratio:2/3;object-fit:cover;width:100%;">`;
    } else {
        photoHTML = `<div class="wine-photo-placeholder">
            <svg width="36" height="64" viewBox="0 0 90 160" fill="none" opacity=".28">
                <rect x="36" y="4" width="18" height="22" rx="4" fill="#c8a96e"/>
                <path d="M28 36 Q20 50 18 70 L18 138 Q18 148 28 150 L62 150 Q72 148 72 138 L72 70 Q70 50 62 36 Z" fill="#c8a96e"/>
            </svg>
        </div>`;
    }

    // Meta rows
    let metaRows = '';
    if (w.producer) metaRows += `<div class="meta-row"><span class="meta-label">Produtor</span><span class="meta-value">${esc(w.producer.name)}</span></div>`;
    const wOrigin = getOrigin(w);
    if (wOrigin) metaRows += `<div class="meta-row"><span class="meta-label">Origem</span><span class="meta-value">${esc(wOrigin)}</span></div>`;
    if (w.alcohol_content) metaRows += `<div class="meta-row"><span class="meta-label">Teor alcoólico</span><span class="meta-value">${Number(w.alcohol_content).toFixed(1)}%</span></div>`;
    if (w.serving_temp_min || w.serving_temp_max) {
        let temp = '';
        if (w.serving_temp_min && w.serving_temp_max) temp = `${w.serving_temp_min}°C – ${w.serving_temp_max}°C`;
        else if (w.serving_temp_min) temp = `${w.serving_temp_min}°C`;
        else temp = `${w.serving_temp_max}°C`;
        metaRows += `<div class="meta-row"><span class="meta-label">Temperatura</span><span class="meta-value">${temp}</span></div>`;
    }

    // Grapes
    let grapesHTML = '';
    if (w.grape_varieties && w.grape_varieties.length) {
        grapesHTML = '<div class="grape-tags">' + w.grape_varieties.map(g =>
            `<span class="grape-tag">${esc(g.name)}${g.percentage ? ' ' + g.percentage + '%' : ''}</span>`
        ).join('') + '</div>';
    }

    // Occasions chips (slide 1 preview)
    let occChipsHTML = '';
    if (hasOccasions) {
        occChipsHTML = `<div><p class="sub-label">Momentos ideais</p><div class="occ-chips-row no-slide-drag">` +
            w.occasions.map(o => `<div class="occ-chip"><span class="oi">${esc(o.icon)}</span>${esc(o.name)}</div>`).join('') +
            '</div></div>';
    }

    // Foods mini (slide 1 preview)
    let foodsMiniHTML = '';
    if (hasFoods) {
        foodsMiniHTML = `<div><p class="sub-label">Harmoniza com</p><div class="food-mini-grid">` +
            w.foods.map(f => {
                const img = f.image
                    ? `<img class="food-mini-img" draggable="false" src="${esc(f.image)}" alt="${esc(f.name)}">`
                    : `<div class="food-mini-ph">🍽️</div>`;
                return `<div class="food-mini">${img}<div class="food-mini-body"><p class="food-mini-name">${esc(f.name)}</p>${f.category ? `<p class="food-mini-cat">${esc(f.category)}</p>` : ''}</div></div>`;
            }).join('') + '</div></div>';
    }

    // Slide 2: Occasions full cards
    let occCardsHTML = '';
    if (hasOccasions) {
        occCardsHTML = `<div><p class="sub-label">Momentos ideais</p><div class="occ-grid">` +
            w.occasions.map(o => `<div class="occ-card"><div class="occ-card-icon">${esc(o.icon)}</div><div class="occ-card-name">${esc(o.name)}</div>${o.description ? `<div class="occ-card-desc">${esc(o.description)}</div>` : ''}</div>`).join('') +
            '</div></div>';
    }

    // Slide 2: Foods full cards
    let foodCardsHTML = '';
    if (hasFoods) {
        foodCardsHTML = `<div><p class="sub-label">Alimentos que combinam</p><div class="food-grid">` +
            w.foods.map(f => {
                const img = f.image
                    ? `<img class="food-img" draggable="false" src="${esc(f.image)}" alt="${esc(f.name)}">`
                    : `<div class="food-ph">🍽️</div>`;
                return `<div class="food-card">${img}<div class="food-body"><p class="food-name">${esc(f.name)}</p>${f.category ? `<p class="food-cat">${esc(f.category)}</p>` : ''}${f.notes ? `<p class="food-notes">${esc(f.notes)}</p>` : ''}</div></div>`;
            }).join('') + '</div></div>';
    } else {
        foodCardsHTML = `<div class="empty-state"><p style="font-size:2rem;margin-bottom:.7rem">🍷</p><p>Harmonizações ainda não cadastradas.</p></div>`;
    }

    // Slide 2: Recipes
    let recipesHTML = '';
    if (hasRecipes) {
        recipesHTML = `<div><p class="sub-label">Receitas</p><div class="recipes-grid">` +
            w.recipes.map(r => {
                const img = r.photo
                    ? `<img class="recipe-img" draggable="false" src="${esc(r.photo)}" alt="${esc(r.name)}">`
                    : `<div class="recipe-ph">👨‍🍳</div>`;
                return `<div class="recipe-card">${img}<div class="recipe-body"><div class="recipe-tags"><span class="recipe-tag difficulty">${esc(r.difficulty ? r.difficulty.charAt(0).toUpperCase() + r.difficulty.slice(1) : '')}</span>${r.prep_time ? `<span class="recipe-tag time">${r.prep_time} min</span>` : ''}</div><h3 class="recipe-name">${esc(r.name)}</h3>${r.description ? `<p class="recipe-desc">${esc(r.description)}</p>` : ''}${r.notes ? `<div class="recipe-note">${esc(r.notes)}</div>` : ''}${r.instructions ? `<div class="recipe-steps">${esc(r.instructions)}</div>` : ''}</div></div>`;
            }).join('') + '</div></div>';
    }

    document.getElementById('app').innerHTML = `
<button class="btn-close" id="closeBtn" title="Fechar">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
</button>
<div class="dots" id="dots">
    <div class="dot active" data-slide="0"></div>
    <div class="dot" data-slide="1"></div>
    ${hasRecipes ? '<div class="dot" data-slide="2"></div>' : ''}
</div>
<div class="track-wrap"><div class="track" id="track">

<div class="slide slide-wine"><div class="wine-slide-inner">
<div class="wine-layout">
    <div class="wine-photo-col">
        <div class="wine-photo-frame">
            <div class="wine-photo-shadow"></div>
            ${photoHTML}
        </div>
        ${starsHTML(w.rating, 'stars-photo-col')}
    </div>
    <div class="wine-title">
        ${w.wine_type ? `<div class="wine-badge"><svg width="7" height="7" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg>${esc(w.wine_type.name)}</div>` : ''}
        <h1 class="wine-name">${esc(w.name)}</h1>
        ${w.vintage ? `<p class="wine-vintage">${esc(w.vintage)}</p>` : ''}
    </div>
    <div class="wine-meta-box"><div class="wine-meta">${metaRows}</div></div>
    <div class="wine-extras">
        ${starsHTML(w.rating, 'stars-extras')}
        ${w.description ? `<p class="wine-desc">${esc(w.description)}</p>` : ''}
        ${grapesHTML}
    </div>
    <div class="wine-harmony">
        ${(hasOccasions || hasFoods) ? '<div class="divider"></div>' : ''}
        ${occChipsHTML}
        ${foodsMiniHTML}
    </div>
</div>
<div class="legal-notice">🔞 Venda proibida para menores de 18 anos &nbsp;·&nbsp; Beba com moderação</div>
</div></div>

<div class="slide slide-pairings">
    <div class="section-header">
        <p class="section-label">Harmonizações</p>
        <h2 class="section-title">O que combina com este vinho?</h2>
        <p class="section-sub">${esc(w.name)}${w.vintage ? ' · ' + esc(w.vintage) : ''}</p>
    </div>
    <div class="pairings-body">
        ${occCardsHTML}
        ${foodCardsHTML}
    </div>
</div>

${hasRecipes ? `<div class="slide slide-recipes">
    <div class="section-header">
        <p class="section-label">Receitas</p>
        <h2 class="section-title">Receitas que combinam com este vinho</h2>
        <p class="section-sub">${esc(w.name)}${w.vintage ? ' · ' + esc(w.vintage) : ''}</p>
    </div>
    <div class="pairings-body">
        ${recipesHTML}
    </div>
</div>` : ''}

</div></div>`;

    initCarousel(TOTAL);
}

// ════════════════════════════════════════════
// SPIRIT
// ════════════════════════════════════════════
function renderSpirit(s) {
    currentView = 'detail';
    const hasOccasions = s.occasions && s.occasions.length;
    const hasDrinks = s.drink_recipes && s.drink_recipes.length;
    const TOTAL = 2;
    const sPhoto = getPhoto(s);

    // Photo
    let photoHTML;
    if (sPhoto) {
        photoHTML = `<img class="spirit-photo" src="${esc(sPhoto)}" alt="${esc(s.name)}" draggable="false" style="aspect-ratio:2/3;object-fit:cover;width:100%;">`;
    } else {
        photoHTML = `<div class="spirit-photo-placeholder">
            <svg width="48" height="64" viewBox="0 0 48 64" fill="none" opacity=".28">
                <rect x="18" y="2" width="12" height="10" rx="2" fill="#c8a96e"/>
                <path d="M14 16 Q10 24 10 34 L10 54 Q10 60 16 62 L32 62 Q38 60 38 54 L38 34 Q38 24 34 16 Z" fill="#c8a96e"/>
            </svg>
        </div>`;
    }

    // Meta rows
    let metaRows = '';
    if (s.producer) metaRows += `<div class="meta-row"><span class="meta-label">Produtor</span><span class="meta-value">${esc(s.producer.name)}</span></div>`;
    if (s.country) metaRows += `<div class="meta-row"><span class="meta-label">Origem</span><span class="meta-value">${esc(s.country.name)}</span></div>`;
    if (s.alcohol_content) metaRows += `<div class="meta-row"><span class="meta-label">Teor alcoólico</span><span class="meta-value">${Number(s.alcohol_content).toFixed(1)}%</span></div>`;

    // Occasions chips (slide 1)
    let occChipsHTML = '';
    if (hasOccasions) {
        occChipsHTML = `<div><p class="sub-label">Momentos ideais</p><div class="occ-chips-row">` +
            s.occasions.map(o => `<div class="occ-chip"><span class="oi">${esc(o.icon)}</span>${esc(o.name)}</div>`).join('') +
            '</div></div>';
    }

    // Drink mini preview (slide 1)
    let drinkMiniHTML = '';
    if (hasDrinks) {
        drinkMiniHTML = `<div><p class="sub-label">Drinks que combinam</p><div class="drink-mini-grid">` +
            s.drink_recipes.map(d => {
                const img = d.photo
                    ? `<img class="drink-mini-img" draggable="false" src="${esc(d.photo)}" alt="${esc(d.name)}">`
                    : `<div class="drink-mini-ph">🍸</div>`;
                return `<div class="drink-mini">${img}<div class="drink-mini-body"><p class="drink-mini-name">${esc(d.name)}</p><p class="drink-mini-diff">${esc(d.difficulty)}</p></div></div>`;
            }).join('') + '</div></div>';
    }

    // Slide 2: Occasions full
    let occCardsHTML = '';
    if (hasOccasions) {
        occCardsHTML = `<div style="padding: 0 clamp(1rem, 3vw, 2.5rem) 2rem; max-width: 1400px; width: 100%; margin-inline: auto;">
            <p class="sub-label">Momentos ideais</p><div class="occ-grid">` +
            s.occasions.map(o => `<div class="occ-card"><div class="occ-card-icon">${esc(o.icon)}</div><div class="occ-card-name">${esc(o.name)}</div>${o.description ? `<div class="occ-card-desc">${esc(o.description)}</div>` : ''}</div>`).join('') +
            '</div></div>';
    }

    // Slide 2: Drink cards
    let drinkCardsHTML = '';
    if (hasDrinks) {
        drinkCardsHTML = `<div style="padding: 0 clamp(1rem, 3vw, 2.5rem) .75rem; max-width: 1400px; width: 100%; margin-inline: auto;"><p class="sub-label">Receitas de drinks</p></div><div class="drinks-grid">` +
            s.drink_recipes.map(d => {
                const img = d.photo
                    ? `<img class="drink-img" draggable="false" src="${esc(d.photo)}" alt="${esc(d.name)}">`
                    : `<div class="drink-ph">🍸</div>`;
                let ingredientsHTML = '';
                if (d.ingredients && d.ingredients.length) {
                    ingredientsHTML = `<div class="drink-ingredients"><p class="drink-ingredients-title">Ingredientes</p>` +
                        d.ingredients.map(ing => `<div class="drink-ingredient"><span class="drink-ingredient-name">${esc(ing.name)}</span><span class="drink-ingredient-qty">${ing.quantity ? esc(ing.quantity) : ''}${ing.unit ? ' ' + esc(ing.unit) : ''}</span></div>`).join('') +
                        '</div>';
                }
                return `<div class="drink-card">${img}<div class="drink-body"><div class="drink-tags"><span class="drink-tag difficulty">${esc(d.difficulty ? d.difficulty.charAt(0).toUpperCase() + d.difficulty.slice(1) : '')}</span>${d.prep_time ? `<span class="drink-tag time">${d.prep_time} min</span>` : ''}</div><h3 class="drink-name">${esc(d.name)}</h3>${d.description ? `<p class="drink-desc">${esc(d.description)}</p>` : ''}${ingredientsHTML}${d.instructions ? `<div class="drink-steps">${esc(d.instructions)}</div>` : ''}</div></div>`;
            }).join('') + '</div>';
    } else {
        drinkCardsHTML = `<div class="empty-state"><p style="font-size:2rem;margin-bottom:.7rem">🍸</p><p>Receitas de drinks ainda não cadastradas.</p></div>`;
    }

    document.getElementById('app').innerHTML = `
<button class="btn-close" id="closeBtn" title="Fechar">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
</button>
<div class="dots" id="dots">
    <div class="dot active" data-slide="0"></div>
    <div class="dot" data-slide="1"></div>
</div>
<div class="track-wrap"><div class="track" id="track">

<div class="slide slide-spirit"><div class="spirit-slide-inner">
<div class="spirit-layout">
    <div class="spirit-photo-col">
        <div class="spirit-photo-frame">
            <div class="spirit-photo-shadow"></div>
            ${photoHTML}
        </div>
    </div>
    <div class="spirit-title">
        ${s.spirit_type ? `<div class="spirit-badge"><svg width="7" height="7" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="5"/></svg>${esc(s.spirit_type.name)}</div>` : ''}
        <h1 class="spirit-name">${esc(s.name)}</h1>
    </div>
    <div class="spirit-meta-box"><div class="spirit-meta">${metaRows}</div></div>
    <div class="spirit-extras">
        ${s.description ? `<p class="spirit-desc">${esc(s.description)}</p>` : ''}
    </div>
    <div class="spirit-drinks">
        ${(hasOccasions || hasDrinks) ? '<div class="divider"></div>' : ''}
        ${occChipsHTML}
        ${drinkMiniHTML}
    </div>
</div>
<div class="legal-notice">🔞 Venda proibida para menores de 18 anos &nbsp;·&nbsp; Beba com moderação</div>
</div></div>

<div class="slide slide-drinks">
    <div class="section-header">
        <p class="section-label">Drinks</p>
        <h2 class="section-title">O que preparar com ${esc(s.name)}?</h2>
        <p class="section-sub">Receitas de coquetéis e drinks</p>
    </div>
    ${occCardsHTML}
    ${drinkCardsHTML}
</div>

</div></div>`;

    initCarousel(TOTAL);
}

// ════════════════════════════════════════════
// CAROUSEL
// ════════════════════════════════════════════
function initCarousel(TOTAL) {
    const INACTIVITY   = 60000;
    const PEEK_DELAY   = 5000;
    const PEEK_REPEAT  = 30000;
    const PEEK_PX      = 72;
    const PEEK_OUT_MS  = 800;
    const PEEK_BACK_MS = 600;

    let current = 0, peeked = false;
    let inactTimer, peekTimer;

    const track = document.getElementById('track');
    const trackWrap = track.parentElement;
    const dots  = document.querySelectorAll('.dot');

    // Set track and slide widths in px so zoom doesn't affect calculations
    function setupWidths() {
        const w = trackWrap.clientWidth;
        track.style.width = (TOTAL * w) + 'px';
        track.querySelectorAll('.slide').forEach(s => s.style.width = w + 'px');
        return w;
    }
    let slideW = setupWidths();
    window.addEventListener('resize', () => { slideW = setupWidths(); applyTransform(current); });

    // Ghost hand overlay
    const oldHint = document.getElementById('swipeHint');
    if (oldHint) oldHint.remove();
    const swipeHint = document.createElement('div');
    swipeHint.id = 'swipeHint';
    swipeHint.className = 'ghost-hand-overlay';
    swipeHint.innerHTML = `
        <div class="ghost-hand">
            <svg viewBox="550 250 1400 2000" xmlns="http://www.w3.org/2000/svg">
              <g transform="translate(0,2500) scale(1,-1)">
                <circle cx="1050" cy="1896.94" r="100" fill="none" stroke="white" stroke-width="24" opacity=".7"/>
                <circle cx="1050" cy="1896.94" r="200" fill="none" stroke="white" stroke-width="24" opacity=".4"/>
                <circle cx="1050" cy="1896.94" r="300" fill="none" stroke="white" stroke-width="24" opacity=".2"/>
                <path d="M1187.182,1703.673 C1187.182,1777.453 1127.372,1837.263 1053.592,1837.263 C979.812,1837.263 920.002,1777.453 920.002,1703.673 L920.002,1113.043 L1187.182,1113.043 Z" fill="white"/>
                <path d="M873.914,1259.529 C837.846,1317.176 761.875,1334.67 704.228,1298.602 C702.668,1297.626 701.108,1296.65 699.548,1295.674 C641.901,1259.607 624.407,1183.636 660.474,1125.989 C765.598,957.969 960.674,646.176 960.674,646.176 L1174.114,779.716 C1174.114,779.716 979.037,1091.509 873.914,1259.529 Z" fill="white"/>
                <path d="M940.967,1699.169 L940.967,760.321 C940.967,507.783 1145.69,303.06 1398.228,303.06 C1650.767,303.06 1855.489,507.783 1855.489,760.321 C1855.489,929.693 1858.288,1081.283 1858.288,1081.283 C1858.288,1081.283 1858.288,1192.836 1858.288,1283.587 C1858.288,1346.914 1819.937,1401.255 1737.923,1395.594 C1628.749,1388.06 1633.772,1154.565 1633.772,1091.237 C1633.772,1091.237 1628.958,1414.519 1628.958,1414.519 C1628.958,1444.93 1616.877,1474.096 1595.373,1495.6 C1573.87,1517.103 1544.704,1529.184 1514.293,1529.184 C1483.882,1529.184 1454.716,1517.103 1433.212,1495.6 C1411.709,1474.096 1399.628,1444.93 1399.628,1414.519 C1399.628,1281.813 1399.628,1081.283 1399.628,1081.283 C1399.628,1081.283 1399.628,1323.534 1399.628,1472.887 C1399.628,1503.298 1387.547,1532.464 1366.043,1553.968 C1344.539,1575.471 1315.374,1587.552 1284.963,1587.552 C1221.635,1587.552 1170.298,1536.215 1170.298,1472.887 C1170.298,1323.534 1170.298,1081.283 1170.298,1081.283 C1170.298,1081.283 1170.298,1493.071 1170.298,1699.169 C1170.298,1762.496 1118.96,1813.834 1055.633,1813.834 C992.305,1813.834 940.967,1762.496 940.967,1699.169 Z" fill="white"/>
              </g>
            </svg>
        </div>
    `;
    swipeHint.style.setProperty('--ghost-delay', (PEEK_DELAY / 1000) + 's');
    document.body.appendChild(swipeHint);

    function dismissHint() {
        if (!swipeHint.classList.contains('hidden')) {
            swipeHint.classList.add('hidden');
            setTimeout(() => swipeHint.remove(), 600);
        }
    }
    const CSS_DUR  = getComputedStyle(document.documentElement).getPropertyValue('--slide-dur').trim() || '380ms';
    const EASE_OUT = getComputedStyle(document.documentElement).getPropertyValue('--ease-out').trim() || 'ease';

    function applyTransform(idx, extraPx = 0) {
        track.style.transform = `translateX(${-idx * slideW - extraPx}px)`;
    }

    function goTo(idx, userAction) {
        if (idx < 0 || idx >= TOTAL) return;
        current = idx;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        applyTransform(current);
        dots.forEach((d, i) => d.classList.toggle('active', i === idx));
        if (userAction) dismissHint();
        resetInactivity();
    }

    function peek() {
        if (peeked || dragging || current >= TOTAL - 1) return;

        // Show hand on first peek
        if (!swipeHint.classList.contains('visible')) {
            swipeHint.classList.add('visible');
        }

        track.style.transition = `transform ${PEEK_OUT_MS}ms var(--ease-out)`;
        applyTransform(current, PEEK_PX);
        setTimeout(() => {
            track.style.transition = `transform ${PEEK_BACK_MS}ms var(--ease-in)`;
            applyTransform(current);
        }, PEEK_OUT_MS + 60);
    }

    function schedulePeek(delay) {
        clearTimeout(peekTimer);
        if (peeked) return;
        peekTimer = setTimeout(() => { peek(); schedulePeek(PEEK_REPEAT); }, delay);
    }

    dots.forEach(d => d.addEventListener('click', () => { peeked = true; goTo(+d.dataset.slide, true); }));

    // Close button
    document.getElementById('closeBtn').addEventListener('click', () => {
        clearTimeout(inactTimer);
        clearTimeout(peekTimer);
        showScanner();
    });

    // Drag
    let dragStartX = 0, dragStartY = 0, dragging = false, dragLocked = false;
    const DRAG_THRESHOLD = 8, SNAP_THRESHOLD = 50;

    document.addEventListener('selectstart', e => { if (dragLocked) e.preventDefault(); });

    track.addEventListener('pointerdown', e => {
        if (e.pointerType === 'touch') return;
        if (e.target.closest('.dot,.btn-close')) return;
        dragging = true; dragLocked = false;
        dragStartX = e.clientX; dragStartY = e.clientY;
        track.style.transition = 'none';
        track.setPointerCapture(e.pointerId);
        resetInactivity();
    });

    track.addEventListener('pointermove', e => {
        if (e.pointerType === 'touch') return;
        if (!dragging) return;
        const dx = Math.abs(e.clientX - dragStartX), dy = Math.abs(e.clientY - dragStartY);
        if (!dragLocked) {
            if (dx < DRAG_THRESHOLD && dy < DRAG_THRESHOLD) return;
            if (dy > dx) { dragging = false; return; }
            dragLocked = true;
        }
        e.preventDefault();
        const off = current * slideW - (e.clientX - dragStartX);
        const clamped = Math.max(-60, Math.min((TOTAL - 1) * slideW + 60, off));
        track.style.transform = `translateX(${-clamped}px)`;
    });

    track.addEventListener('pointerup', e => {
        if (e.pointerType === 'touch') return;
        if (!dragging || !dragLocked) { dragging = false; return; }
        dragging = false;
        const delta = e.clientX - dragStartX;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        if (Math.abs(delta) > SNAP_THRESHOLD) {
            peeked = true; clearTimeout(peekTimer);
            goTo(delta < 0 ? current + 1 : current - 1, true);
        } else {
            applyTransform(current);
        }
    });

    track.addEventListener('pointercancel', e => {
        if (e.pointerType === 'touch') return;
        if (!dragging) return;
        dragging = false;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        applyTransform(current);
    });

    // Touch fallback
    let touchStartX = 0, touchStartY = 0, touchLocked = false, touchDragging = false;

    track.addEventListener('touchstart', e => {
        if (e.target.closest('.dot,.btn-close')) return;
        const t = e.touches[0];
        touchStartX = t.clientX; touchStartY = t.clientY;
        touchDragging = true; touchLocked = false;
        resetInactivity();
    }, { passive: true });

    track.addEventListener('touchmove', e => {
        if (!touchDragging) return;
        const t = e.touches[0];
        const dx = Math.abs(t.clientX - touchStartX), dy = Math.abs(t.clientY - touchStartY);
        if (!touchLocked) {
            if (dx < DRAG_THRESHOLD && dy < DRAG_THRESHOLD) return;
            if (dy > dx) { touchDragging = false; return; }
            touchLocked = true;
            track.style.transition = 'none';
        }
        e.preventDefault();
        const off = current * slideW - (t.clientX - touchStartX);
        const clamped = Math.max(-60, Math.min((TOTAL - 1) * slideW + 60, off));
        track.style.transform = `translateX(${-clamped}px)`;
    }, { passive: false });

    track.addEventListener('touchend', e => {
        if (!touchDragging || !touchLocked) { touchDragging = false; return; }
        touchDragging = false;
        const t = e.changedTouches[0];
        const delta = t.clientX - touchStartX;
        track.style.transition = `transform ${CSS_DUR} ${EASE_OUT}`;
        if (Math.abs(delta) > SNAP_THRESHOLD) {
            peeked = true; clearTimeout(peekTimer);
            goTo(delta < 0 ? current + 1 : current - 1, true);
        } else {
            applyTransform(current);
        }
    });

    // Keyboard
    document.addEventListener('keydown', e => {
        if (currentView !== 'detail') return;
        if (e.key === 'ArrowRight') { peeked = true; goTo(current + 1, true); }
        if (e.key === 'ArrowLeft')  { peeked = true; goTo(current - 1, true); }
    });

    function resetInactivity() {
        clearTimeout(inactTimer);
        inactTimer = setTimeout(() => showScanner(), INACTIVITY);
    }

    goTo(0);
    schedulePeek(PEEK_DELAY);
}

// ── Barcode scanner input (USB barcode readers send keystrokes) ──
document.addEventListener('keydown', e => {
    if (currentView !== 'scanner') return;
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
showScanner();
