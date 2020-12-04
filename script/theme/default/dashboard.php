<?php defined("APP") or die() // Main Page ?>
<div class="content">
<div class="container-xl">
   <!-- Page title -->
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col-auto">
            <div class="page-pretitle">
               Overview
            </div>
            <h2 class="page-title">
               Dashboard
            </h2>
         </div>
         <div class="col-auto ml-auto d-print-none">
            <?php if ($this->isAdmin || in_array(1, $this->userAccess)): ?>
            <a href="<?=PROOT?>/links/new" class="btn btn-primary ml-3 d-none d-sm-inline-block" >
               <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z"></path>
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
               </svg>
               Create new link
            </a>
            <a href="<?=PROOT?>/links/new" class="btn btn-primary ml-3 d-sm-none btn-icon " data-toggle="modal" aria-label="Create new report">
               <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z"></path>
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
               </svg>
            </a>
            <?php endif; ?>
         </div>
      </div>
   </div>
   <!-- Content here -->
   <div class="row row-deck row-cards">
      <div class="col-sm-6 col-lg-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center">
                  <div class="subheader" style="font-size: 14px;">Total Links</div>
                  <div class="ml-auto lh-1">
                  </div>
               </div>
               <div class="h1 mt-1"><?=number_format($aData['total_links'])?></div>
            </div>
         </div>
      </div>
      <div class="col-sm-6 col-lg-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center">
                  <div class="subheader" style="font-size: 14px;">Total Users</div>
                  <div class="ml-auto lh-1">
                  </div>
               </div>
               <div class="h1 mt-1"><?=number_format($aData['total_users'])?></div>
            </div>
         </div>
      </div>
      <div class="col-sm-6 col-lg-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center">
                  <div class="subheader" style="font-size: 14px;">Total Views</div>
                  <div class="ml-auto lh-1">
                  </div>
               </div>
               <div class="h1 mt-1"><?=number_format($aData['total_views'])?></div>
            </div>
         </div>
      </div>
      <div class="col-sm-6 col-lg-3">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center">
                  <div class="subheader" style="font-size: 14px;">Num of auto embeds</div>
                  <div class="ml-auto lh-1">
                  </div>
               </div>
               <div class="h1 mt-1"><?=number_format($aData['num_of_auto_embeds'])?></div>
            </div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="card card-sm">
            <div class="card-body d-flex align-items-center" style="justify-content:space-around">
               <span class="bg-light text-white stamp mr-3">
               <img src="<?=getThemeURI()?>/assets/img/icons/GDrive.png" height="30" alt="">
               </span>
               <div class="mr-3 lh-sm">
                  <div class="strong text-center">
                     <?=number_format($aData['gdrive_links'])?>
                  </div>
                  <div class="text-muted">GDrive links</div>
               </div>
               <span class="bg-light text-white stamp mr-3">
               <img src="<?=getThemeURI()?>/assets/img/icons/GPhoto.png" height="30" alt="">
               </span>
               <div class="mr-3 lh-sm">
                  <div class="strong text-center">
                     <?=number_format($aData['gphoto_links'])?>
                  </div>
                  <div class="text-muted">GPhoto link</div>
               </div>
               <span class="bg-light text-white stamp mr-3">
               <img src="<?=getThemeURI()?>/assets/img/icons/OneDrive.png" height="30" alt="">
               </span>
               <div class="mr-3 lh-sm">
                  <div class="strong text-center">
                     <?=number_format($aData['onedrive_links'])?>
                  </div>
                  <div class="text-muted">OneDrive links</div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-sm-6">
         <div class="card card-sm">
            <div class="card-body d-flex align-items-center" style="justify-content:space-around">
               <span class="bg-success text-white stamp mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z"></path>
                     <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                     <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                     <line x1="16" y1="21" x2="16" y2="19"></line>
                     <line x1="19" y1="16" x2="21" y2="16"></line>
                     <line x1="3" y1="8" x2="5" y2="8"></line>
                     <line x1="8" y1="3" x2="8" y2="5"></line>
                  </svg>
               </span>
               <div class="mr-3 lh-sm">
                  <div class="strong text-center">
                     <?=number_format($aData['active_links'])?>
                  </div>
                  <div class="text-muted">Active links</div>
               </div>
               <span class="bg-danger text-white stamp mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z"></path>
                     <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                     <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                     <line x1="16" y1="21" x2="16" y2="19"></line>
                     <line x1="19" y1="16" x2="21" y2="16"></line>
                     <line x1="3" y1="8" x2="5" y2="8"></line>
                     <line x1="8" y1="3" x2="8" y2="5"></line>
                  </svg>
               </span>
               <div class="mr-3 lh-sm">
                  <div class="strong text-center">
                     <?=number_format($aData['broken_links'])?>
                  </div>
                  <div class="text-muted">Broken links</div>
               </div>
               <span class="bg-secondary text-white stamp mr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z"></path>
                     <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                     <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                     <line x1="16" y1="21" x2="16" y2="19"></line>
                     <line x1="19" y1="16" x2="21" y2="16"></line>
                     <line x1="3" y1="8" x2="5" y2="8"></line>
                     <line x1="8" y1="3" x2="8" y2="5"></line>
                  </svg>
               </span>
               <div class="mr-3 lh-sm">
                  <div class="strong text-center">
                     <?=number_format($aData['deleted_links'])?>
                  </div>
                  <div class="text-muted">Deleted links</div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-12">
         <div class="card">
            <div class="card-body">
               <div id="chartContainerx" style="height: 400px; width: 100%;"></div>
            </div>
         </div>
      </div>
      <div class="col-md-6 col-lg-7">
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">Most Active Links</h4>
            </div>
            <div class="table-responsive">
               <table class="table card-table table-vcenter">
                  <thead>
                     <tr>
                        <th>Link title</th>
                        <th>User</th>
                        <th>source</th>
                        <th>Visitors</th>
                        <th>Created At</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach ($aData['mostViewed'] as $link): ?>
                     <tr>
                        <td class="text-reset">
                           <?=$link['title']?>
                        </td>
                        <td class="text-muted"><?=$link['username']?></td>
                        <td class="text-center">
                           <img src="<?=getThemeURI()?>/assets/img/icons/<?=$link['type']?>.png" height="20" alt="">
                        </td>
                        <td class="text-muted">  <?=$link['views']?></td>
                        <td class="text-muted">  <?=Main::dateFormat($link['created_at'])?></td>
                        <td class="text-muted">
                           <a href="javascript:void(0)" class="btn btn-sm btn-secondary copy-link" title="copy plyer link" data-url="<?=Main::getDomain().PROOT.'/video/'.$link['slug']?>">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                                 <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                              </svg>
                           </a>
                        </td>
                     </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="col-md-6 col-lg-5">
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">Recently Created Links</h4>
            </div>
            <div class="table-responsive">
               <table class="table card-table table-vcenter">
                  <thead>
                     <tr>
                        <th>Link title</th>
                        <th>User</th>
                        <th>Source</th>
                        <th> </th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach ($aData['recentlyAdded'] as $link): ?>
                     <tr>
                        <td class="text-reset">
                           <?=$link['title']?>
                        </td>
                        <td class="text-muted"><?=$link['username']?></td>
                        <td class="text-center">
                           <img src="<?=getThemeURI()?>/assets/img/icons/<?=$link['type']?>.png" height="20" alt="">
                        </td>
                        <td class="text-muted">
                           <a href="javascript:void(0)" class="btn btn-sm btn-secondary copy-link" title="copy plyer link" data-url="<?=Main::getDomain().PROOT.'/video/'.$link['slug']?>">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                                 <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                              </svg>
                           </a>
                        </td>
                     </tr>
                     <?php endforeach; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
