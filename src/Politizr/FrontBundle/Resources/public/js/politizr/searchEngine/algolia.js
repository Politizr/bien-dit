
// init search if search box is available
if ($('#searchInputAlgolia').length > 0) {
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

  // https://www.algolia.com/doc/api-reference/api-parameters/filters/?language=javascript
  var circleUuid = $('#searchInputAlgolia').attr('circleUuid');
  // console.log(circleUuid);
  if (circleUuid) {
    search.addWidget(
      instantsearch.widgets.configure({
        filters: 'circleUuid:' + circleUuid
      })
    );
  }

  search.addWidget(
    instantsearch.widgets.searchBox({
      container: '#searchInputAlgolia',
      autofocus: false,
      reset: false,
      magnifier: false,
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
}
