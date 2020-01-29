$(document).ready(function(){
  $('#example1').DataTable();
  $('.select2').select2();
  //Data picker
  $('#datapicker').datapicker({
    autoclose: true
  });
  //iCheck for checkbox and radio inputs
  $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
  });  
});