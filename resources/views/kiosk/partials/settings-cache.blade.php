<script>
(function(){
    try {
        var s = JSON.parse(localStorage.getItem('kiosk_settings') || 'null');
        if (!s) return;
        var r = document.documentElement;
        function hex2rgb(h){var m=/^#([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(h);return m?[parseInt(m[1],16),parseInt(m[2],16),parseInt(m[3],16)]:null;}
        function lum(c){return 0.299*c[0]+0.587*c[1]+0.114*c[2];}
        function adj(c,a){return '#'+c.map(function(v){return('0'+Math.max(0,Math.min(255,v+a)).toString(16)).slice(-2);}).join('');}
        if (s.element_scale && s.element_scale !== 1) r.style.zoom = s.element_scale;
        if (s.color_primary) {
            r.style.setProperty('--primary', s.color_primary);
            r.style.setProperty('--primary-dk', s.color_primary);
            var p = hex2rgb(s.color_primary);
            if (p) r.style.setProperty('--primary-rgb', p.join(','));
        }
        if (s.color_background) {
            var b = hex2rgb(s.color_background), dark = b && lum(b) < 128;
            r.style.setProperty('--bg', s.color_background);
            r.style.setProperty('--bg-alt', s.color_background);
            r.style.setProperty('--surface', s.color_background);
            r.style.background = s.color_background;
            if (b) {
                r.style.setProperty('--surface-alt', adj(b, dark ? 18 : -12));
                if (dark) { r.style.setProperty('--border','rgba(255,255,255,.1)'); r.style.setProperty('--shadow','rgba(0,0,0,.3)'); }
            }
        }
        if (s.color_text) {
            r.style.setProperty('--text', s.color_text);
            var t = hex2rgb(s.color_text);
            if (t) r.style.setProperty('--muted', 'rgba('+t.join(',')+',.55)');
        }
    } catch(e) {}
})();
</script>
