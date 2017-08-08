var search = instantsearch({
  // Replace with your own values
  appId: 'PCH7L1BPQO',
  apiKey: '5d5cd40806f8b3267a59b513ecb3551a', // search only API key, no ADMIN key
  indexName: 'dev_POLITIZR',
  urlSync: true,
  searchFunction: function(helper) {
    var searchResults = $('#algoliaResults');
    if (helper.state.query === '') {
      searchResults.hide();
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
      empty: "We didn't find any results for the search <em>\"{{query}}\"</em>"
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
    container: '#categories',
    attributeName: 'type',
    limit: 10,
    templates: {
      header: 'Catégories'
    }
  })
);



search.start();