// var client = algoliasearch("PCH7L1BPQO", "5d5cd40806f8b3267a59b513ecb3551a");
// var index = client.initIndex('dev_POLITIZR');
// 
// index.setSettings({
//   searchableAttributes: [
//     'title',
//     'description',
//   ],
//   // customRanking: ['desc(popularity)'],
// });
// 
// 

var search = instantsearch({
  // Replace with your own values
  appId: 'PCH7L1BPQO',
  apiKey: '5d5cd40806f8b3267a59b513ecb3551a', // search only API key, no ADMIN key
  indexName: 'dev_POLITIZR',
  urlSync: true
});

search.addWidget(
  instantsearch.widgets.searchBox({
    container: '#search-input'
  })
);

search.addWidget(
  instantsearch.widgets.hits({
    container: '#hits',
    hitsPerPage: 10,
    templates: {
      item: document.getElementById('hit-template').innerHTML,
      empty: "We didn't find any results for the search <em>\"{{query}}\"</em>"
    }
  })
);

search.addWidget(
  instantsearch.widgets.pagination({
    container: '#pagination'
  })
);

search.start();