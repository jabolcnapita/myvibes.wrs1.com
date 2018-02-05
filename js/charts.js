var chart = c3.generate({
  data: {
    url: 'chart-followers.php',
    type: 'pie'
  },
    pie: {
    },
    bindto: '#chart'
}); 

var chart2 = c3.generate({
  data: {
    url: 'chart-likes.php',
    type: 'pie'
  },
  pie: {
  },
  bindto: '#chart2'
});