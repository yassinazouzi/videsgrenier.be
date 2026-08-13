@extends('admin.layout')
@section('titre', 'Réglages')

@section('contenu')

<form method="POST" action="{{ route('admin.reglages.update') }}" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="bo-cols">
    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete">
          <h3>Bulle WhatsApp</h3>
          <label class="bo-toggle">
            <input type="checkbox" name="whatsapp_actif" value="1"
                   @checked(old('whatsapp_actif', $reglages['whatsapp_actif'] ?? '0') === '1')>
            <span class="piste"></span>
          </label>
        </div>

        <p class="sous" style="margin-bottom:16px">
          Désactivée, la bulle disparaît du site sans toucher au code.
        </p>

        <div class="bo-champ">
          <label for="whatsapp_numero">Numéro international (sans + ni espace)</label>
          <input type="text" id="whatsapp_numero" name="whatsapp_numero" inputmode="numeric"
                 value="{{ old('whatsapp_numero', $reglages['whatsapp_numero'] ?? '') }}" placeholder="32491644913">
        </div>

        <div class="bo-champ">
          <label for="whatsapp_message">Message pré-rempli</label>
          <textarea id="whatsapp_message" name="whatsapp_message" rows="3">{{ old('whatsapp_message', $reglages['whatsapp_message'] ?? '') }}</textarea>
        </div>

        <div class="bo-champ">
          <label for="whatsapp_infobulle">Texte de l’info-bulle</label>
          <input type="text" id="whatsapp_infobulle" name="whatsapp_infobulle"
                 value="{{ old('whatsapp_infobulle', $reglages['whatsapp_infobulle'] ?? '') }}">
        </div>

        <div class="bo-champ">
          <label for="whatsapp_horaires">Horaires affichés</label>
          <input type="text" id="whatsapp_horaires" name="whatsapp_horaires"
                 value="{{ old('whatsapp_horaires', $reglages['whatsapp_horaires'] ?? '') }}">
        </div>
      </div>
    </div>

    <div>
      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Contact public</h3></div>

        <div class="bo-champ">
          <label for="telephone_public">Téléphone affiché sur le site</label>
          <input type="text" id="telephone_public" name="telephone_public"
                 value="{{ old('telephone_public', $reglages['telephone_public'] ?? '') }}">
        </div>

        <div class="bo-champ">
          <label for="email_devis">E-mail de réception des devis</label>
          <input type="email" id="email_devis" name="email_devis"
                 value="{{ old('email_devis', $reglages['email_devis'] ?? '') }}">
        </div>

        <p class="sous">
          Ce téléphone alimente aussi le JSON-LD LocalBusiness. Gardez-le identique
          à celui de votre fiche Google Business Profile (cohérence NAP).
        </p>
      </div>

      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Fond animé de l'accueil</h3></div>

        @php
          $heroVideoActuel = $reglages['hero_video'] ?? '';
          $heroVideoType = match(true) {
            $heroVideoActuel === '' => null,
            (bool) preg_match('~youtube\.com|youtu\.be~i', $heroVideoActuel) => 'YouTube',
            str_starts_with($heroVideoActuel, 'storage/hero/') => 'fichier uploadé',
            default => 'lien externe',
          };
        @endphp

        <p class="sous" style="margin-bottom:14px">
          Priorité du fond : <strong>vidéo</strong> (si configurée ci-dessous) →
          <strong>slider photo manuel</strong> (si des photos y sont ajoutées) →
          photos « après » de vos réalisations publiées → dégradé animé par défaut.
        </p>

        @if($heroVideoType)
          <div class="bo-champ">
            <span class="badge badge-nouveau">Vidéo active : {{ $heroVideoType }}</span>
            @if($heroVideoType !== 'YouTube')
              <div class="sous" style="margin-top:6px;word-break:break-all">{{ $heroVideoActuel }}</div>
            @endif
          </div>
        @endif

        <div class="bo-champ">
          <label for="hero_video">Lien YouTube ou URL d'un fichier vidéo direct</label>
          <input type="text" id="hero_video" name="hero_video"
                 placeholder="https://www.youtube.com/watch?v=… ou https://…/chantier.mp4"
                 value="{{ old('hero_video', $heroVideoActuel) }}">
        </div>

        <div class="bo-champ">
          <label for="hero_video_fichier">Ou uploader un fichier vidéo (MP4/WebM/MOV, 50 Mo max)</label>
          <input type="file" id="hero_video_fichier" name="hero_video_fichier" accept="video/mp4,video/webm,video/quicktime">
          <span class="sous">Un fichier uploadé ici remplace le lien saisi au-dessus.</span>
        </div>

        @if($heroVideoType)
          <label style="display:flex;align-items:center;gap:10px;font-size:14px;margin-bottom:16px">
            <input type="checkbox" name="hero_video_supprimer" value="1">
            Supprimer la vidéo actuelle et revenir au slider photo / dégradé
          </label>
        @endif

        <a href="{{ route('admin.reglages.slider-photos') }}" class="bo-btn" style="width:100%;justify-content:center">
          🖼 Gérer les photos du slider (upload manuel)
        </a>
      </div>

      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Réseaux sociaux</h3></div>

        <div class="bo-champ">
          <label for="facebook_url">Facebook</label>
          <input type="url" id="facebook_url" name="facebook_url" placeholder="https://www.facebook.com/videsgrenierbe"
                 value="{{ old('facebook_url', $reglages['facebook_url'] ?? '') }}">
        </div>

        <div class="bo-champ">
          <label for="instagram_url">Instagram</label>
          <input type="url" id="instagram_url" name="instagram_url" placeholder="https://www.instagram.com/videsgrenierbe"
                 value="{{ old('instagram_url', $reglages['instagram_url'] ?? '') }}">
        </div>

        <div class="bo-champ">
          <label for="tiktok_url">TikTok</label>
          <input type="url" id="tiktok_url" name="tiktok_url" placeholder="https://www.tiktok.com/@videsgrenierbe"
                 value="{{ old('tiktok_url', $reglages['tiktok_url'] ?? '') }}">
        </div>

        <p class="sous">
          Laissés vides, aucune icône n'apparaît en pied de page. Renseignés, ils alimentent
          aussi le JSON-LD <code>sameAs</code> — un signal utile pour que Google relie
          clairement votre site à vos comptes officiels.
        </p>
      </div>

      <div class="bo-panneau">
        <div class="bo-panneau-tete"><h3>Site &amp; mesure</h3></div>

        <div class="bo-champ">
          <label for="site_titre">Titre du site</label>
          <input type="text" id="site_titre" name="site_titre"
                 value="{{ old('site_titre', $reglages['site_titre'] ?? '') }}">
        </div>

        <div class="bo-champ">
          <label for="ga_id">Identifiant Google Analytics</label>
          <input type="text" id="ga_id" name="ga_id"
                 value="{{ old('ga_id', $reglages['ga_id'] ?? '') }}" placeholder="G-XXXXXXX">
        </div>
      </div>

      <button type="submit" class="bo-btn bo-btn-cobalt" style="width:100%;justify-content:center">
        Enregistrer les réglages
      </button>
    </div>
  </div>
</form>

@endsection
