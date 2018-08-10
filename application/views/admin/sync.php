<div class="row clearfix">
    <div class="col-xs-12">
        <div class="card">
            <div class="body">
            	<blockquote class="m-b-25">
            		<p><b>[Peringatan]</b> Update database cukup dilakukan sekali tiap semester.</p>
                </blockquote>
                <!-- <blockquote class="m-b-25">
            		<p><b>[Peringatan]</b> Dengan menekan tombol berikut, sistem akan melakukan sinkronisasi data dengan basis data milik ITS.</p>
                </blockquote> -->
            </div>
        </div>
    </div>
</div>
<div class="row clearfix">
    <div class="col-md-8 col-md-offset-2 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>UPDATE</h2>
            </div>
            <div class="body">
            	<form class="form-horizontal" autocomplete="off" role="form" action="<?php echo base_url(); ?>Admin/sync2" method="post">
            		<div class="row clearfix">
            			<div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            				<label for="tahun">Tahun</label>
            			</div>
            			<div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            				<div class="form-group">
            					<div class="form-line">
            						<input type="number" id="tahun" name="tahun" class="form-control" placeholder="Masukkan tahun ajaran" required>
            					</div>
            				</div>
            			</div>
            		</div>
            		<div class="row clearfix">
            			<div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            			</div>
            			<div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            				<div class="form-group">
            					<div class="form-line">
            						<div class="keterangan"></div>
            					</div>
            				</div>
            			</div>
            		</div>
            		<div class="row clearfix">
            			<div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            				<label for="semester">Semester</label>
            			</div>
            			<div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            				<div class="form-group">
            					<div class="form-line">
            						<select class="form-control show-tick" name="semester" required>
            							<option disabled selected style="display:none">Pilih Semester</option>
            							<option value="1">Gasal</option>
            							<option value="2">Genap</option>
            						</select>
            					</div>
            				</div>
            			</div>
            		</div>
            		<div class="row clearfix">
            			<div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
            				<button type="submit" class="btn btn-primary m-t-15 waves-effect">UPDATE</button>
            			</div>
            		</div>
            	</form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function()
    {
        $('#tahun').change(function()
        {
            var tahun = $('#tahun').val();
            var tahun = parseInt(tahun);
            $('div.keterangan').html('<small>(Tahun ajaran ' + tahun.toString() + '/' + (tahun + 1).toString() + ')</small>');
        });
    });
</script>