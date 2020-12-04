<?php defined("APP") or die() ?>
<footer class="footer footer-transparent">
   <div class="container">
      <div class="row text-center align-items-center flex-row-reverse">
         <div class="col-lg-auto ml-lg-auto">
            <ul class="list-inline list-inline-dots mb-0">
               <li class="list-inline-item"><a href="https://www.fiverr.com/codyseller" target="_blank" class="link-secondary">Develop By <b>@CodySeller</b> </a></li>
            </ul>
         </div>
         <div class="col-12 col-lg-auto mt-3 mt-lg-0">
            Copyright © 2020
            <a href="https://www.fiverr.com/codyseller" target="_blank"  class="link-secondary">GD Player</a>.
            All rights reserved.
         </div>
      </div>
   </div>
</footer>
</div>
</div>
<!-- Libs JS -->
<div class="modal modal-blur fade" id="modal-logout" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-body">
            <div class="modal-title">Are you sure?</div>
            <div>Are you sure you want to logout now?</div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-link link-secondary mr-auto" data-dismiss="modal">Cancel</button>
            <a href="<?=PROOT?>/logout"  class="btn btn-danger" >Yes, logout</a>
         </div>
      </div>
   </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="<?=getThemeURI()?>/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- Tabler Core -->
<script src="https://cdn.datatables.net/v/bs4/dt-1.10.22/datatables.min.js"></script>
<script src="<?=getThemeURI()?>/assets/js/tabler.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/canvasjs/1.7.0/canvasjs.min.js" integrity="sha512-FJ2OYvUIXUqCcPf1stu+oTBlhn54W0UisZB/TNrZaVMHHhYvLBV9jMbvJYtvDe5x/WVaoXZ6KB+Uqe5hT2vlyA==" crossorigin="anonymous"></script>
<script>
   $( function() {
     $( "#sortable, #vastAds" ).sortable();
     $( "#sortable, #vastAds" ).disableSelection();
   } );
</script>
<script>
   const PROOT = '<?=PROOT?>';
</script>
<script src="<?=getThemeURI()?>/assets/js/custom.js"></script>
<?php if (isset($this->dataPointx)): ?>
<?php
   $dataPointx = $this->dataPointx;

     ?>
<script>
   var d = new Date();
   var month = new Array();
   month[0] = "January";
   month[1] = "February";
   month[2] = "March";
   month[3] = "April";
   month[4] = "May";
   month[5] = "June";
   month[6] = "July";
   month[7] = "August";
   month[8] = "September";
   month[9] = "October";
   month[10] = "November";
   month[11] = "December";
   var n = month[d.getMonth()];
   window.onload = function () {



   var linksChart = new CanvasJS.Chart("chartContainerx",
   {
     title:{
       text: n + " Links Charts"
     },
     axisX:{
       interval:7,
       intervalType: "day"
     },
     axisY: {
    title: "Number of links",
    suffix: "",
    prefix: "",
    interval:10,
   },
     data: [
     {
       type: "column",
       markerSize: 10,
       xValueType: "dateTime",
       dataPoints: [ <?php echo $dataPointx; ?> ]
    }
    ]
   });

     linksChart.render();

   }



</script>
<?php endif; ?>
<script>
   document.body.style.display = "block"
</script>
</body>
</html>
