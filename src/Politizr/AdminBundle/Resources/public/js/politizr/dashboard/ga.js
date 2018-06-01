(function(w,d,s,g,js,fjs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(cb){this.q.push(cb)}};
  js=d.createElement(s);fjs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fjs.parentNode.insertBefore(js,fjs);js.onload=function(){g.load('analytics')};
}(window,document,'script'));

gapi.analytics.ready(function() {
  // Authorize the user.
  var CLIENT_ID = $('#gaZone').attr('clientId');
  var VIEW_ID = $('#gaZone').attr('viewId');

  gapi.analytics.auth.authorize({
    container: 'auth-button',
    clientid: CLIENT_ID,
  });

  // Create the timeline chart.
  var timeline = new gapi.analytics.googleCharts.DataChart({
    reportType: 'ga',
    query: {
      metrics: 'ga:sessions',
      dimensions: 'ga:date',
      'ids': 'ga:' + VIEW_ID,
      'start-date': '30daysAgo',
      'end-date': 'yesterday'
    },
    chart: {
      container: 'timeline',
      type: 'LINE',
      options: {
        width: '100%'
      }
    }
  });

  // Create the bouncerate chart.
  var bouncerate = new gapi.analytics.googleCharts.DataChart({
    reportType: 'ga',
    query: {
      metrics: 'ga:bounceRate',
      dimensions: 'ga:date',
      'ids': 'ga:' + VIEW_ID,
      'start-date': '30daysAgo',
      'end-date': 'yesterday'
    },
    chart: {
      container: 'bouncerate',
      type: 'LINE',
      options: {
        width: '100%'
      }
    }
  });

//   // Create the trafficsource chart.
//   var trafficsource = new gapi.analytics.googleCharts.DataChart({
//     reportType: 'ga',
//     query: {
//       metrics: 'ga:organicSearches',
//       dimensions: 'ga:hasSocialSourceReferral',
//       'ids': 'ga:' + VIEW_ID,
//       'start-date': '30daysAgo',
//       'end-date': 'yesterday'
//     },
//     chart: {
//       container: 'trafficsource',
//       type: 'PIE',
//       options: {
//         width: '100%'
//       }
//     }
//   });

  timeline.execute();
  bouncerate.execute();
//   trafficsource.execute();

  // Logout
  gapi.analytics.auth.on('signIn', function() {
    // console.log('signin ga');
    $('#gaLogout').show();
  });

  gapi.analytics.auth.on('signOut', function() {
    // console.log('signout ga');
    $('#gaLogout').hide();
  });

  $("body").on("click", "span[action='gaLogout']", function(e) {
    // console.log('*** logout ga');
    gapi.analytics.auth.signOut();
  });
});
