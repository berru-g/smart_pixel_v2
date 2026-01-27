// public/tracker.js - Version SaaS
(function(window, document, script, trackerId) {
    window.SmartPixel = window.SmartPixel || function() {
        (window.SmartPixel.q = window.SmartPixel.q || []).push(arguments);
    };
    
    SmartPixel.load = function(trackingCode) {
        // Configuration
        this.config = {
            endpoint: 'http://localhost/smart_pixel_V2/public/pixel.php',
            trackingCode: trackingCode,
            sessionId: this.getOrCreateSessionId(),
            pageLoaded: false
        };
        
        // Track pageview immédiatement
        this.trackPageView();
        
        // Événements
        this.trackClicks();
        this.trackScroll();
        this.trackExit();
    };
    
    // Méthodes
    SmartPixel.getOrCreateSessionId = function() {
        let sessionId = sessionStorage.getItem('sp_session_id');
        if (!sessionId) {
            sessionId = 'sess_' + Math.random().toString(36).substr(2, 12);
            sessionStorage.setItem('sp_session_id', sessionId);
        }
        return sessionId;
    };
    
    SmartPixel.trackPageView = function() {
        const params = new URLSearchParams({
            t: this.config.trackingCode,
            sid: this.config.sessionId,
            vp: window.innerWidth + 'x' + window.innerHeight,
            ref: document.referrer || 'direct',
            tz: new Date().getTimezoneOffset(),
            l: navigator.language
        });
        
        // Envoyer le pixel
        new Image().src = this.config.endpoint + '?' + params;
        
        // Marquer comme chargé
        this.config.pageLoaded = true;
    };
    
    SmartPixel.trackEvent = function(eventName, eventData = {}) {
        if (!this.config.pageLoaded) return;
        
        const params = new URLSearchParams({
            t: this.config.trackingCode,
            sid: this.config.sessionId,
            e: eventName,
            ed: JSON.stringify(eventData)
        });
        
        new Image().src = this.config.endpoint + '?' + params;
    };
    
    SmartPixel.trackClicks = function() {
        document.addEventListener('click', (e) => {
            // Ignorer certains éléments
            if (e.target.tagName === 'SCRIPT' || 
                e.target.tagName === 'LINK' || 
                e.target.closest('[data-sp-ignore]')) {
                return;
            }
            
            setTimeout(() => {
                this.trackEvent('click', {
                    tag: e.target.tagName,
                    id: e.target.id || '',
                    class: e.target.className || '',
                    text: e.target.textContent?.substr(0, 100) || '',
                    href: e.target.href || '',
                    x: e.clientX,
                    y: e.clientY,
                    path: this.getElementPath(e.target)
                });
            }, 100);
        }, { passive: true });
    };
    
    SmartPixel.getElementPath = function(element) {
        const path = [];
        while (element && element.nodeType === Node.ELEMENT_NODE) {
            let selector = element.nodeName.toLowerCase();
            if (element.id) {
                selector += '#' + element.id;
            } else if (element.className && typeof element.className === 'string') {
                selector += '.' + element.className.split(' ')[0];
            }
            path.unshift(selector);
            element = element.parentNode;
        }
        return path.slice(0, 3).join(' > ');
    };
    
    // Initialisation automatique si data-sp-id présent
    document.addEventListener('DOMContentLoaded', function() {
        const scriptEl = document.querySelector('script[data-sp-id]');
        if (scriptEl) {
            const trackingCode = scriptEl.getAttribute('data-sp-id');
            SmartPixel.load(trackingCode);
        }
    });
    
})(window, document, 'script');