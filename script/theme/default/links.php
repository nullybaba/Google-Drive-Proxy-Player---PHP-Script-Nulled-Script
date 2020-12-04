<?php defined("APP") or die() // Links Page ?>
<div class="content">
<div class="container-xl">
   <!-- Page title -->
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col-auto">
            <h2 class="page-title">
               <?=ucwords($type)?> Links - ( <?=count($links)?> )
            </h2>
         </div>
         <div class="col-auto ml-auto ">
            <?php $dty = (!isset($isDeleted)) ? 1 : 0; ?>
            <a href="javascript:void(0)" class="btn btn-danger delete-selecetd-items ml-3 d-none" data-type="<?=$dty?>">
               <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z"></path>
                  <line x1="4" y1="7" x2="20" y2="7"></line>
                  <line x1="10" y1="11" x2="10" y2="17"></line>
                  <line x1="14" y1="11" x2="14" y2="17"></line>
                  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
               </svg>
               delete selected links (<b></b>)
            </a>
         </div>
      </div>
   </div>
   <!-- Content here -->
   <div class="card">
      <div class="table-responsive">
         <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
               <tr>
                  <th class="w-1"><input class="form-check-input m-0 align-middle" id="select_all" type="checkbox"></th>
                  <th class="w-1">ID.
                  </th>
                  <th>Title
                  </th>
                  <th>User
                  </th>
                  <th>Source
                  </th>
                  <th>Alt link
                  </th>
                  <th class="text-center">Views
                  </th>
                  <th>
                     Created At.
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z"/>
                        <polyline points="7 8 3 12 7 16" />
                        <polyline points="17 8 21 12 17 16" />
                        <line x1="14" y1="4" x2="10" y2="20" />
                     </svg>
                  </th>
                  <th>Last Updated At
                  </th>
                  <th></th>
               </tr>
            </thead>
            <tbody>
               <?php foreach($links as $link): ?>
               <tr data-id="<?=$link['id']?>">
                  <td><input class="form-check-input m-0 align-middle delete-item" type="checkbox" aria-label="Select invoice"></td>
                  <td><span class="text-muted">#<?=$link['id']?></span></td>
                  <td><a href="#" class="text-reset title" tabindex="-1" title="<?=$link['title']?>"><?=$link['title']?></a></td>
                  <td>
                     <b> <i>  <?=ucwords($link['username'])?></i> </b>
                  </td>
                  <td class="text-center">
                     <img src="<?=getThemeURI()?>/assets/img/icons/<?=$link['type']?>.png" height="20" alt="">
                  </td>
                  <td class="text-center">
                    <?php if (!empty($link['alt_data']) && $link['alt_data'] != '[]'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                              <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                            </svg>
                  <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <circle cx="5" cy="12" r="1"></circle>
                              <circle cx="12" cy="12" r="1"></circle>
                              <circle cx="19" cy="12" r="1"></circle>
                            </svg>
                    <?php endif; ?>

                  </td>
                  <td class="text-center">
                     <?=$link['views']?>
                  </td>
                  <td>
                     <?=Main::dateFormat($link['created_at'])?>
                  </td>
                  <td >
                     <?=Main::dateFormat($link['updated_at'])?>
                  </td>
                  <td class="text-right">
                     <?php if (!isset($isDeleted)): ?>
                     <div class="btn-list flex-nowrap">
                        <!-- edit-link -->
                        <?php $elClass = ($this->isAdmin || in_array(2, $this->userAccess) || $this->userId == $link['user_id'] ) ? 'edit-link' : 'no-access'; ?>
                        <?php
                           $gid = ($link['type'] != 'GDrive') ? $link['data'] : 'https://drive.google.com/file/d/' . $link['driveId'] . '/view?usp=sharing' ;

                           ?>
                        <a href="<?=PROOT?>/links/edit/<?=$link['id']?>" class="btn btn-sm btn-secondary" title="edit link">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6-6a6 6 0 0 1 -8 -8l3.5 3.5"></path>
                           </svg>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary copy-link" title="copy plyer link" data-url="<?=Main::getDomain().PROOT.'/video/'.$link['slug']?>">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                              <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                           </svg>
                        </a>
                        <?php $dlClass = ($this->isAdmin || in_array(3, $this->userAccess)) ? 'delete-link' : 'no-access'; ?>
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary  <?=$dlClass?>" title="delete link"  data-type="1">
                           <svg class="icon icon-md m-0" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path>
                              <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path>
                           </svg>
                        </a>
                     </div>
                     <?php else: ?>
                     <div class="btn-list flex-nowrap">
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary restore-link">
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z"></path>
                              <path d="M4 11a8.1 8.1 0 1 1 .5 4m-.5 5v-5h5"></path>
                           </svg>
                        </a>
                        <?php $dlClass = ($this->isAdmin || in_array(3, $this->userAccess)) ? 'delete-link' : 'no-access'; ?>
                        <a href="javascript:void(0)" class="btn btn-sm btn-secondary <?=$dlClass?>" data-type="0">
                           <svg class="icon icon-md m-0" width="1em" height="1em" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M7.5 7.5A.5.5 0 018 8v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V8a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V8z"></path>
                              <path fill-rule="evenodd" d="M16.5 5a1 1 0 01-1 1H15v9a2 2 0 01-2 2H7a2 2 0 01-2-2V6h-.5a1 1 0 01-1-1V4a1 1 0 011-1H8a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM6.118 6L6 6.059V15a1 1 0 001 1h6a1 1 0 001-1V6.059L13.882 6H6.118zM4.5 5V4h11v1h-11z" clip-rule="evenodd"></path>
                           </svg>
                        </a>
                     </div>
                     <?php endif; ?>
                  </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>
