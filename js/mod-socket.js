var socket = io('http://localhost:3000');

socket.on('notify', function(data) {
  if(data.type === 'new_report') {
    document.querySelector(".notify").classList.add("active");
  }
});

function getReports(){
  /*TODO: */
  fetch();
}
