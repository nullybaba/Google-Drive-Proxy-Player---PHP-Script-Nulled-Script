<?php defined("APP") or die() // Links Page ?>
<div class="content">
   <div class="container-xl">
      <div class="page-header">
         <div class="row align-items-center">
            <div class="col-auto">
               <h2 class="page-title">
                  Bulk Import
               </h2>
            </div>
         </div>
      </div>
      <!-- Content here -->
      <div class="row">
         <div class="col-lg-9">
            <div class="card">
               <div class="card-body">
                  <form class="">
                     <div class="mb-3">
                        <label class="form-label">Insert Goolge Drive Links : </label>
                        <textarea class="form-control" rows="20" id="linkList" name="" placeholder="https://drive.google.com/file/d/xxxxxxxxxxxxxxxxxxxxxxxxxx/view?usp=sharing , https://drive.google.com/file/d/xxxxxxxxxxxxxxxxxxxxxxxxx/view?usp=sharing"></textarea>
                     </div>
                     <div class="mb-3     overflow-hidden">
                        <button type="button" class="btn btn-primary btn-lg float-right" id="bulkImport" >Import Now</button>
                     </div>
                  </form>
                  <div class="alert bg-light d-none">
                     <p class="mb-0 lstatus">
                        <span class="mr-3 tl"><span class="badge bg-info">Total Links :  </span> <b> 100</b> </span>
                        <span class="mr-3 sl"><span class="badge bg-success">Success : </span> <b> 98</b></span>
                        <span class="mr-3 fl"><span class="badge bg-danger">Failed :  </span> <b> 2</b></span>
                     </p>
                  </div>
                  <div class="alert alert-danger flinks d-none">
                     <h3>Import Failed Links</h3>
                     <ul>
                     </ul>
                  </div>
                  <ul class="w-100">
                     <li>*Only support for google drive links</li>
                     <li>*Separate each link by COMMA (,)</li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
