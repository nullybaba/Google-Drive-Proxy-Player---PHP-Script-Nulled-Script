<?php defined("APP") or die() // Main Page ?>
<div class="content">
<div class="container-xl">
   <!-- Page title -->
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col-auto">
            <h2 class="page-title">
               Advertisement
            </h2>
         </div>
      </div>
   </div>
   <!-- Content here -->
   <form class="" action="<?=$_SERVER['REQUEST_URI']?>" method="post">
      <div class="row">
         <div class="col-lg-6">
            <div class="card">
               <div class="card-body">
                  <div id="vastAds">
                     <?php if (!empty($this->config['vastAds'])):
                        $vastAds = json_decode($this->config['vastAds'], true);
                        ?>
                     <?php foreach ($vastAds as $k => $v): ?>
                     <div class="mb-3 ui-state-default w-100 float-left">
                        <label class="form-label">VAST Tag</label>
                        <div class="input-group mb-2 " style="justify-content: space-between;">
                           <input type="url" class="form-control vastTag" name="vast[<?=$k+1?>][tag]" value="<?=$v['tag']?>" placeholder="https://www.domain.com/adtag.xml">
                           <span class="input-group-text" style="cursor:move">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <polyline points="16 4 20 4 20 8"></polyline>
                                 <line x1="14" y1="10" x2="20" y2="4"></line>
                                 <polyline points="8 20 4 20 4 16"></polyline>
                                 <line x1="4" y1="20" x2="10" y2="14"></line>
                                 <polyline points="16 20 20 20 20 16"></polyline>
                                 <line x1="14" y1="14" x2="20" y2="20"></line>
                                 <polyline points="8 4 4 4 4 8"></polyline>
                                 <line x1="4" y1="4" x2="10" y2="10"></line>
                              </svg>
                           </span>
                        </div>
                        <div class="">
                           <input type="text" class="form-control vastOffset float-left  mt-2" style="width:100px" name="vast[<?=$k+1?>][offset]" placeholder="offset" value="<?=$v['offset']?>">
                           <a href="#" class="text-danger float-right removeVast" >remove</a>
                        </div>
                     </div>
                     <div class="clearfix">
                     </div>
                     <?php endforeach; ?>
                     <?php endif; ?>
                  </div>
                  <button type="button" class="btn btn-primary btn-sm" id="addVastTag" name="">Add VAST ad</button>
               </div>
            </div>
         </div>
         <div class="col-lg-6">
            <div class="card">
               <div class="card-body">
                  <div class="mb-3">
                     <label class="form-label">Pop ads</label>
                     <textarea name="popads" class="form-control" rows="15"><?=Main::unsanitized($this->config['popAds'])?></textarea>
                  </div>
                  <button type="submit" class="btn btn-lg btn-primary float-right">Save changes</button>
               </div>
            </div>
         </div>
      </div>
   </form>
   <div class="mb-3 ui-state-default d-none w-100 float-left" id="vastItem">
      <label class="form-label">VAST Tag</label>
      <div class="input-group mb-2" style="justify-content: space-between;">
         <input type="url" class="form-control vastTag" name="vast[1][tag]" placeholder="https://www.domain.com/adtag.xml" >
         <span class="input-group-text" style="cursor:move">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
               <path stroke="none" d="M0 0h24v24H0z"></path>
               <polyline points="16 4 20 4 20 8"></polyline>
               <line x1="14" y1="10" x2="20" y2="4"></line>
               <polyline points="8 20 4 20 4 16"></polyline>
               <line x1="4" y1="20" x2="10" y2="14"></line>
               <polyline points="16 20 20 20 20 16"></polyline>
               <line x1="14" y1="14" x2="20" y2="20"></line>
               <polyline points="8 4 4 4 4 8"></polyline>
               <line x1="4" y1="4" x2="10" y2="10"></line>
            </svg>
         </span>
      </div>
      <div class="">
         <input type="text" class="form-control vastOffset float-left  mt-2" style="width:100px" name="vast[1][offset]" placeholder="offset" value="">
         <a href="#" class="text-danger float-right removeVast" >remove</a>
      </div>
   </div>
</div>
