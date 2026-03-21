@if(!empty($settings))
<script>
(function () {
    var s = @json($settings);
    var r = document.documentElement;

    function hexToRgb(hex) {
        var m = /^#([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return m ? [parseInt(m[1],16), parseInt(m[2],16), parseInt(m[3],16)] : null;
    }
    function luminance(rgb) {
        return 0.299 * rgb[0] + 0.587 * rgb[1] + 0.114 * rgb[2];
    }
    function adjustColor(rgb, amount) {
        return rgb.map(function(c) { return Math.max(0, Math.min(255, c + amount)); });
    }
    function rgbToHex(rgb) {
        return '#' + rgb.map(function(c) { return ('0' + c.toString(16)).slice(-2); }).join('');
    }

    if (s.element_scale && s.element_scale !== 1) {
        r.style.zoom = s.element_scale;
    }

    if (s.color_primary) {
        r.style.setProperty('--primary', s.color_primary);
        r.style.setProperty('--primary-dk', s.color_primary);
        var pRgb = hexToRgb(s.color_primary);
        if (pRgb) r.style.setProperty('--primary-rgb', pRgb.join(','));
    }

    if (s.color_background) {
        var bgRgb = hexToRgb(s.color_background);
        var isDark = bgRgb && luminance(bgRgb) < 128;

        r.style.setProperty('--bg', s.color_background);
        r.style.setProperty('--bg-alt', s.color_background);
        r.style.setProperty('--surface', s.color_background);
        r.style.background = s.color_background;
        document.body.style.background = s.color_background;
        var bgEl = document.querySelector('.bg');
        if (bgEl) bgEl.style.background = s.color_background;

        if (bgRgb) {
            var surfaceAlt = rgbToHex(adjustColor(bgRgb, isDark ? 18 : -12));
            r.style.setProperty('--surface-alt', surfaceAlt);

            if (isDark) {
                r.style.setProperty('--border', 'rgba(255,255,255,.1)');
                r.style.setProperty('--shadow', 'rgba(0,0,0,.3)');
            }
        }
    }

    if (s.color_text) {
        r.style.setProperty('--text', s.color_text);
        var tRgb = hexToRgb(s.color_text);
        if (tRgb) {
            r.style.setProperty('--muted', 'rgba(' + tRgb.join(',') + ',.55)');
        }
    }

    try { localStorage.setItem('kiosk_settings', JSON.stringify(s)); } catch(e) {}

    if (s.logo_url) {
        var logo = document.querySelector('.logo');
        var logoSub = document.querySelector('.logo-sub');
        if (logo) {
            var img = document.createElement('img');
            img.src = s.logo_url;
            img.alt = 'Logo';
            img.draggable = false;
            img.style.cssText = 'max-width:clamp(120px,30vw,280px);max-height:clamp(60px,12vh,120px);object-fit:contain;margin-bottom:1.5rem;';
            logo.parentNode.insertBefore(img, logo);
            logo.style.display = 'none';
            if (logoSub) logoSub.style.display = 'none';
        }
    }
})();
</script>
@endif
