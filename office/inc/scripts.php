<!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Are you sure you want to logout?</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="./logout">Logout</a>
        </div>
      </div>
    </div>
  </div>


<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/60e6b868649e0a0a5ccb230b/1fa2k0o5l';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->


  <!-- Bootstrap core JavaScript-->
  <!-- <script src="vendor/jquery/jquery.min.js"></script> -->
 <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->

 <!-- <script
            src="http://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script> -->
    <!-- <script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/dataTables.bootstrap.min.js"></script> -->

  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <!-- <script src="vendor/chart.js/Chart.min.js"></script> -->

  <!-- Page level custom scripts -->
  <!-- <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script> -->

  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<!--//Metis Menu -->




  <!-- <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script> -->



 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" type="text/javascript"></script>

 <!-- <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
<script type="text/javascript">
    function deleteRowwIncome(position){
        $("#pos"+position).remove();
        var currcount = $('#counterr').val();
        var newccount = currcount - 1;
        console.log(newccount);
      
          /////reset to disabled incase there is cancellation
              if(newccount == 0){
                // document.getElementById("cmd_add_entries").style.visibility = 'none';
                //  $('#cmd_entries').attr('disabled', 'disabled');
                  $('#cmd_entries').attr('disabled', 'disabled');
                  $('#cmd_update_farm_entries').attr('disabled', 'enabled');
              }

        document.getElementById("counterr").value = newccount;
        document.getElementById("counterr2").textContent = newccount;
    }


    function deleteRowwExpense(position){
        $("#pos"+position).remove();
        var currcount = $('#counterr').val();
        var newccount = currcount - 1;
        console.log(newccount);
      
          /////reset to disabled incase there is cancellation
              if(newccount == 0){
                // document.getElementById("cmd_add_entries").style.visibility = 'none';
              $('#cmd_entries').attr('disabled', 'disabled');
              }

        document.getElementById("counterr").value = newccount;
        document.getElementById("counterr2").textContent = newccount;
    }



    $('#cmd_entries').attr('disabled', 'disabled');
    // $('#cmd_entries').attr('disabled', 'disabled');
    // document.getElementById("cmd_add_entries").style.visibility = 'none';



</script>

<script type="text/javascript">
     
      $(document).ready(function() {
         
          // $('#dataTableServer').DataTable( {
         
       var all_members = $('#dataTableServer').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          "ajax": "server_tables/all_members.php",
          // 'pagingType': 'numbers'
            "order": [[ 2, "asc" ]],
            "columnDefs": [
            { "render": all_members_action,
            "data": null,         
            "targets": [0], "width": "9%", "targets": 0 },
            ]
          } );

       var all_home_cell = $('#home_cell_table').DataTable({
          "scrollX": true,
          "processing": true,
          "serverSide": true,
          "ajax": "server_tables/all_home_cells.php",
          // 'pagingType': 'numbers'
            // "order": [[ 2, "asc" ]],
            // "columnDefs": [
            // { "render": all_home_cells_action,
            // "data": null,         
            // "targets": [0], "width": "9%", "targets": 0 },
            // ]
          } );
      
          function all_members_action(data, type, full) {
          return '<a href="./edit_member.php?id='+full[0]+'" class="btn btn-sm btn-primary">Edit</a>';
          }

      } );

</script>

<script>
  <?php //include('submit_form.js'); ?>
</script>

  <script src="config/jquery_funcs.js"></script>
  <script src="inc/form_submits.js"></script>


  <script type="text/javascript">

$(document).ready(function () {

         // toastr.info('Page Loaded!');
         $('.js-example-basic-single').select2();
         $('.js-example-basic-single2').select2();
         // $('.js-example-basic-singleyy').select2();
         $('.js-example-basic-multiple').select2();
         $('.js-example-basic-multiple2').select2();
         $('.js-example-basic-multiple3').select2();
         $('.js-example-basic-multiple4').select2();
         $('#marital_status_married').hide();
         $('#cmd_sms_blackist').hide();
         $('#cmd_sms_blackist_undo').hide();
         $('#display_ebook_price').hide();
         $('#display_hardcopy_book_price').hide();


         $(".js-example-basic-multiple_with_tags").select2({
          tags: true,
          tokenSeparators: [',', ' ']
         })
   
        // js-example-basic-multiple
        $('.logintest').click(function (e) {
              e.preventDefault();
              toastr.error("Testing lllllll", "Caution!");
        });

    
         // SIgn Up
      submit_form('signup_form', 'cmd_signup', 'Signup', false, 'signup', 'signing up...', "Your registration was successful...", 'index');

        // Add Deposit
    submit_form('add_deposit_form', 'cmd_add_deposit', 'Add Deposit', false, 'add_deposit', 'adding deposit...', "Deposit was successfully added...", 'make_deposit');

    // Add Deposit
    submit_form('add_transfer_form', 'cmd_transfer_funds', 'Transfer Funds', false, 'transfer_funds', 'transferring funds...', "Funds was successfully transfered...", 'transfer_funds');
   
    // Login 
    submit_form('login_form', 'cmd_login', 'Login', true, 'login', 'logging in...', "Login was successful...", 'home');



});


</script>

</body>

</html>