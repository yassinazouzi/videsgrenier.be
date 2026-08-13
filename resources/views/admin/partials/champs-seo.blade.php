<div class="bo-panneau">
  <div class="bo-panneau-tete"><h3>SEO</h3></div>

  <div class="bo-champ">
    <label for="meta_title">Meta title</label>
    <input type="text" id="meta_title" name="meta_title" maxlength="190"
           value="{{ old('meta_title', $entite->meta_title) }}"
           oninput="document.getElementById('compteur-title').textContent = this.value.length">
    <span class="sous"><span id="compteur-title">{{ strlen($entite->meta_title ?? '') }}</span>/60 caractères recommandés</span>
  </div>

  <div class="bo-champ">
    <label for="meta_description">Meta description</label>
    <textarea id="meta_description" name="meta_description" rows="3" maxlength="320"
              oninput="document.getElementById('compteur-desc').textContent = this.value.length">{{ old('meta_description', $entite->meta_description) }}</textarea>
    <span class="sous"><span id="compteur-desc">{{ strlen($entite->meta_description ?? '') }}</span>/155 caractères recommandés</span>
  </div>

  <p class="sous">Laissés vides, le titre et la description sont générés automatiquement.</p>
</div>
