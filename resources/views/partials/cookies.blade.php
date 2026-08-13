@php $gaId = $reglages['ga_id'] ?? null; @endphp

@if($gaId && $gaId !== 'G-XXXXXXX')
  {{-- Consentement refusé par défaut (RGPD) : gtag n'est injecté qu'après acceptation explicite. --}}
  <div class="cookies" id="cookies" hidden>
    <div class="cookies-texte">
      <strong>Cookies de mesure d’audience</strong>
      <p>Nous utilisons Google Analytics pour comprendre comment le site est consulté. Rien n’est déposé sans votre accord.</p>
    </div>
    <div class="cookies-actions">
      <button type="button" class="btn" id="cookies-refuser">Refuser</button>
      <button type="button" class="btn btn-cobalt" id="cookies-accepter">Accepter</button>
      <a href="{{ route('confidentialite') }}" class="mono muet" style="font-size:12px">En savoir plus</a>
    </div>
  </div>

  <script>
  (function () {
    var CLE = 'vg_consentement';
    var banniere = document.getElementById('cookies');
    var choix = localStorage.getItem(CLE);

    function chargerAnalytics() {
      if (window.__vgGaCharge) return;
      window.__vgGaCharge = true;
      var s = document.createElement('script');
      s.async = true;
      s.src = 'https://www.googletagmanager.com/gtag/js?id={{ $gaId }}';
      document.head.appendChild(s);
      window.dataLayer = window.dataLayer || [];
      window.gtag = function () { window.dataLayer.push(arguments); };
      gtag('js', new Date());
      gtag('config', '{{ $gaId }}', { anonymize_ip: true });
      document.dispatchEvent(new Event('vg:analytics-pret'));
    }

    if (choix === 'accepte') {
      chargerAnalytics();
    } else if (choix !== 'refuse') {
      banniere.hidden = false;
    }

    document.getElementById('cookies-accepter').addEventListener('click', function () {
      localStorage.setItem(CLE, 'accepte');
      banniere.hidden = true;
      chargerAnalytics();
    });

    document.getElementById('cookies-refuser').addEventListener('click', function () {
      localStorage.setItem(CLE, 'refuse');
      banniere.hidden = true;
    });
  })();
  </script>
@endif
