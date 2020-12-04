<?php defined("APP") or die() // Links Page
   ?>
<div class="content">
   <div class="container-xl">
      <div class="page-header">
         <div class="row align-items-center">
            <div class="col-auto">
               <h2 class="page-title">
                  <?=ucwords($this->pf) ?> Link
               </h2>
            </div>
         </div>
      </div>
      <!-- Content here -->
      <div class="row">
         <div class="col-lg-8">
            <div class="card">
               <div class="card-body">
                  <?php if (isset($err)): ?>
                  <?php if (!empty($err)): ?>
                  <div class="alert alert-danger">
                     <ul class="mb-0">
                        <?php foreach ($err as $e): ?>
                        <li><?=$e
                           ?></li>
                        <?php
                           endforeach; ?>
                     </ul>
                  </div>
                  <?php
                     else: ?>
                  <div class="alert alert-success">
                     Link Saved Successfully !
                  </div>
                  <?php
                     endif; ?>
                  <?php
                     endif; ?>
                  <?php if (!$isEdit): ?>
                  <form class="" action="<?=$_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data" id="linkForm" data-id="newForm" >
                     <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="murl" placeholder="My Example File Title">
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Main Drive Link</label>
                        <input type="url" class="form-control" name="murl" placeholder="https://drive.google.com/file/d/1o6p1s6Gl971k1enen3XnyDV2G6vYwhHc/view?usp=sharing">
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Alternative Link</label>
                        <input type="url" class="form-control" name="alt_link" placeholder="https://cdn.plyr.io/static/View_From_A_Blue_Moon_Trailer-576p.mp4">
                        <small>*You can insert <u>custom video link (mp4,mkv etc) </u>, or <u>google drive link</u> as alternative link   </small>
                     </div>


                     <hr>
                     <div class="" id="sortable">
                     </div>
                     <div class="mb-3">
                        <button type="button" class="btn btn-primary btn-sm add-sub"  >Add subtitle</button>
                     </div>
                     <div class="mb-3     overflow-hidden">
                        <button type="submit" class="btn btn-primary btn-lg float-right"  >Save</button>
                     </div>
                  </form>
                  <?php
                     else: ?>
                  <form class="" action="<?=$_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data" id="linkForm" data-id="editForm" >
                     <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="<?=$link['title'] ?>" placeholder="My Example File Title">
                     </div>
                     <input type="text" name="id" value="<?=$link['id'] ?>" hidden>
                     <div class="mb-3">
                        <label class="form-label">Main Drive Link</label>
                        <input type="url" class="form-control" value="https://drive.google.com/file/d/<?=$link['driveId'] ?>?usp=sharing" name="murl" placeholder="https://drive.google.com/file/d/1o6p1s6Gl971k1enen3XnyDV2G6vYwhHc/view?usp=sharing">
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Alternative Link</label>
                        <input type="url" class="form-control" name="alt_link" value="<?=$altLink?>" placeholder="https://cdn.plyr.io/static/View_From_A_Blue_Moon_Trailer-576p.mp4">
                        <small>*You can insert <u>custom video link (mp4,mkv etc) </u>, or <u>google drive link</u> as alternative link   </small>
                     </div>

                     <hr>
                     <div class="" id="sortable">
                        <?php if (!empty($link['subtitles']) && $link['subtitles'] != 'NULL'):
                           $subs = json_decode('[' . $link['subtitles'] . ']', true);
                           ?>
                        <?php foreach ($subs as $k => $sub):
                           $fname = substr(str_replace(PROOT . '/uploads/subtitles/', '', $sub['file']), 0, 50) . '...';
                           ?>
                        <div class="row ui-state-default" >
                           <div class="col-lg-4">
                              <div class="mb-3">
                                 <?php if (!empty($this->config['sublist'])):
                                    // dnd(json_decode($this->config['sublist']));
                                    $sublist = json_decode($this->config['sublist'], true);
                                    ?>
                                 <select class="form-select subLabel" name="sub[<?=$k + 1 ?>][label]">
                                    <?php foreach ($sublist as $s): ?>
                                    <option value="<?=$s
                                       ?>" <?php if ($s == $sub['label']) echo 'selected'; ?> ><?=ucwords($s) ?></option>
                                    <?php
                                       endforeach; ?>
                                 </select>
                                 <?php
                                    endif; ?>
                                 <a href="javascript:void(0)" class="text-danger removeSub">remove</a>
                              </div>
                           </div>
                           <input type="text" class="sublink" name="sub[<?=$k + 1 ?>][file]" value="<?=$sub['file'] ?>" hidden >
                           <div class="col-lg-8">
                              <div class="mb-3">
                                 <div class="input-group mb-2" style="justify-content: space-between;">
                                    <input type="file" class="subFile"  name="sub[<?=$k + 1 ?>][file]">
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
                                 <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                       <path stroke="none" d="M0 0h24v24H0z"></path>
                                       <path d="M15 7l-6.5 6.5a1.5 1.5 0 0 0 3 3l6.5 -6.5a3 3 0 0 0 -6 -6l-6.5 6.5a4.5 4.5 0 0 0 9 9 l6.5 -6.5"></path>
                                    </svg>
                                    <span class="badge bg-dark"><?=$fname ?></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <?php
                           endforeach; ?>
                        <?php
                           endif; ?>
                     </div>
                     <div class="mb-3">
                        <button type="button" class="btn btn-primary btn-sm add-sub"  >Add subtitle</button>
                     </div>
                     <div class="mb-3     overflow-hidden">
                        <button type="submit" class="btn btn-primary btn-lg float-right"  >Save</button>
                     </div>
                  </form>
                  <?php
                     endif; ?>
               </div>
            </div>
         </div>
         <div class="col-lg-4">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Recently Created Links</h4>
               </div>
               <table class="table card-table table-vcenter">
                  <thead>
                     <tr>
                        <th>Link</th>
                        <th></th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php if (!empty($ralinks)): ?>
                     <?php foreach ($ralinks as $ralink): ?>
                     <tr>
                        <td><?=$ralink['title'] ?></td>
                        <td class="text-muted">
                           <a href="javascript:void(0)" class="btn btn-sm btn-secondary copy-link" title="copy plyer link" data-url="<?=Main::getDomain() . PROOT . '/video/' . $ralink['slug'] ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z"></path>
                                 <path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5"></path>
                                 <path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5"></path>
                              </svg>
                           </a>
                        </td>
                     </tr>
                     <?php
                        endforeach; ?>
                     <?php
                        endif; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="row ui-state-default d-none" id="fiuop" >
   <div class="col-lg-4">
      <div class="mb-3">
         <?php if (!empty($this->config['sublist'])):
            // dnd(json_decode($this->config['sublist']));
            $sublist = json_decode($this->config['sublist'], true);
            ?>
         <select class="form-select subLabel" name="sub[1][label]">
            <?php foreach ($sublist as $s): ?>
            <option value="<?=$s
               ?>"><?=ucwords($s) ?></option>
            <?php
               endforeach; ?>
         </select>
         <?php
            endif; ?>
         <a href="javascript:void(0)" class="text-danger removeSub">remove</a>
      </div>
   </div>
   <input type="text" class="sublink" name="" value="" hidden >
   <div class="col-lg-8">
      <div class="mb-3">
         <div class="input-group mb-2" style="justify-content: space-between;">
            <input type="file" class="subFile"  name="sub[1][file]">
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
      </div>
   </div>
</div>
