var search = instantsearch({
  // Replace with your own values
  appId: $('#searchModal').attr('app'),
  apiKey: $('#searchModal').attr('key'), // search only API key, no ADMIN key
  indexName: $('#searchModal').attr('index'),
  urlSync: true,
  searchFunction: function(helper) {
    var searchResults = $('#algoliaResults');
    var startSearch = $('#startSearch');
    
    startSearch.hide();

    if (helper.state.query === '') {
      searchResults.hide();
      startSearch.show();
      return;
    }
    helper.search();
    searchResults.show();
  }
});

search.addWidget(
  instantsearch.widgets.searchBox({
    container: '#searchInputAlgolia',
    autofocus: false,
  })
);

search.addWidget(
  instantsearch.widgets.infiniteHits({
    container: '#hits',
    hitsPerPage: 10,
    templates: {
      item: document.getElementById('hit-template').innerHTML,
      empty: "Aucun résultat pour <em>\"{{query}}\"</em>"
    },
    showMoreLabel: "Plus de résultats",
  })
);

// search.addWidget(
//   instantsearch.widgets.pagination({
//     container: '#pagination'
//   })
// );

search.addWidget(
  instantsearch.widgets.menu({
    container: '#categoriesResults',
    attributeName: 'typeLabel',
    limit: 10,
    templates: {
      header: 'Filtrer par'
    }
  })
);

search.start();