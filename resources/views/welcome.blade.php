<!DOCTYPE html>
<html lang="fr">
<body>
  <h1>Produits</h1>
  <ul id="list"></ul>
  <input id="name" placeholder="Nom"> <input id="price" placeholder="Prix">
  <button id="add">Ajouter</button>
  <p id="error" role="alert"></p>

  <script>
    const API = '/api/products';
    const headers = { 'Content-Type': 'application/json', 'Accept': 'application/json' };
    const nameInput = document.getElementById('name');
    const priceInput = document.getElementById('price');
    const list = document.getElementById('list');
    const error = document.getElementById('error');

    async function load() {
      const response = await fetch(API, { headers });
      if (!response.ok) throw new Error('Impossible de charger les produits.');
      const items = await response.json();
      list.replaceChildren(...items.map(product => {
        const item = document.createElement('li');
        item.textContent = `${product.name} : ${product.price} MAD`;
        return item;
      }));
    }

    document.getElementById('add').addEventListener('click', async () => {
      error.textContent = '';
      const response = await fetch(API, {
        method: 'POST',
        headers,
        body: JSON.stringify({ name: nameInput.value.trim(), price: priceInput.value.trim() }),
      });
      const result = await response.json();
      if (!response.ok) {
        error.textContent = Object.values(result.errors ?? {}).flat().join(' ') || result.message;
        return;
      }

      nameInput.value = '';
      priceInput.value = '';
      load();
    });

    load().catch(() => {
      error.textContent = 'Impossible de charger les produits.';
    });
  </script>
</body>
</html>