<script>				
			var j = jQuery.noConflict();
			
			j( document ).ready(function() {
				if (j('#sp_disclaimer').length > 0) {
					if (j.cookie('disclaimer_accepted') != 'yes') {
						j('#sp_disclaimer').modal({backdrop:'static',keyboard:false})
						j('#sp_disclaimer').on('hide',function(){

							
						})
					}			
				}
				
				
				
			});

			function Accept() {
						j.cookie('disclaimer_accepted','yes',{expires:30,path: '/'});
						j("#sp_disclaimer").modal ('hide'); 
			};			
	</script>
	
	<div id="sp_disclaimer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="sp_disclaimer" aria-hidden="true" style="z-index:9999 !Important">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Terms of Use Agreement</h3>
		  </div>
		  <div class="modal-body">
<h4>The Canadian Real Estate Association</h4>
<p>REALTOR®, REALTORS®, and the REALTOR® logo are certification marks that are owned by REALTOR® Canada Inc. and licensed exclusively to The Canadian Real Estate Association (CREA). These
certification marks identify real estate professionals who aremembers of CREA and who must abide by CREA’s ByLaws, Rules, and the REALTOR® Code. The MLS® trademark and
the MLS® logo are owned by CREA and identify the quality ofservices provided by real estate professionals who aremembers of CREA</p>
<p>The information contained on this site is based in whole orin part on information thatis provided by members of The Canadian Real Estate Association, who are responsible forits accuracy.
CREA reproduces and distributes this information as a service for its members and assumes no responsibility forits accuracy</p>
<p>This website is operated by a brokerage or salesperson who is a member of The Canadian Real Estate Association<p>
<p>The listing content on this website is protected by copyright and other laws, and is intended solely forthe private, non commercial use by individuals. Any other reproduction, distribution or use ofthe content, in whole or in part, is specifically forbidden. The prohibited usesinclude commercial use, “screen scraping”, “database scraping”, and any other activity intended to collect,store,reorganize ormanipulate data onthe pages produced by or displayed on this website<p>
<h4>Sanskript Solutions</h4>
<p>Sanskript Solution, Inc. assumes no responsibility forits accuracy.</p>
		  </div>
		  <div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary" onclick="Accept();">Accept</button>
		  </div>
	</div>
