<?php
    if (isset($_POST['search'])) {
        require_once 'db.php';

     
        $search = $_POST['search'];

       $query = "SELECT * FROM delivery_schedule WHERE so_no LIKE '%" . $search . "%' ORDER BY id DESC limit 1" ;
        $result = mysqli_query($conns,$query);
            if(!$result) die(mysqli_error());
            while($row = mysqli_fetch_object($result)){
      
?>  
  <form action="schedulemanuals.php" method="post"  role="form"> 
<div class="col-md-12" >
                       <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">SO NO :</label>  
                            <div class="col-md-3">
                                <input type="text" id="search"  name="so_no" class="form-control" required value="<?= $row->so_no; ?>" >
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Schedule Date :</label>  
                            <div class="col-md-3">
                               <input type="text" class="form-control" id="filter" name="schedule_date"
                                    placeholder="Pilih Tanggal"  value="<?php echo date('d/m/Y'); ?>">
                            </div>
                        </div>
                    </div>
                      <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Sales Name :</label>  
                            <div class="col-md-3">
                                <input type="" name="sales_name" class="form-control" value="<?= $row->sales_name; ?>"> 
                            </div>
                        </div>
                    </div>
                </div>
                     <div class="col-md-12 ">
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Customer Name :</label>  
                            <div class="col-md-3">
                                <input type="" name="customer_name" class="form-control" value="<?= $row->customer_name; ?>">
                            </div>
                        </div>
                    </div>
      
                
                      <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Project Location :</label>  
                            <div class="col-md-3">
                                <input type="" name="project_location" class="form-control" value="<?= $row->project_location; ?>">
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Project Address :</label>  
                            <div class="col-md-3">
                                <input type="" name="project_address" class="form-control" value="<?= $row->project_address; ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ">
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Customer ID:</label>  
                            <div class="col-md-3">
                                <input type="" name="customer_id" class="form-control" value="<?= $row->customer_id; ?>"> 
                            </div> 
                        </div>
                    </div>
      
                
                      <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Project ID :</label>  
                            <div class="col-md-3">
                                <input type="" name="project_id" class="form-control" value="<?= $row->project_id; ?>">
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Mix Design:</label>  
                            <div class="col-md-3">
                                <input type="" name="product_code" class="form-control" value="<?= $row->product_code; ?>"> 
                            </div>
                        </div>
                    </div>
                </div>
                   <div class="col-md-12 ">
                     <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Vol Awal :</label>  
                            <div class="col-md-3"> 
                                <input type="" name="qty_so" class="form-control" value="<?= $row->qty_so; ?>" >
                            </div>
                        </div>
                    </div>
        
                     <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">D/O Vol :</label>  
                            <div class="col-md-3">
                                <input type="" name="deliv_order_vol" class="form-control" value="<?= $row->deliv_order_vol; ?>">
                            </div>
                        </div>
                    </div>
    
                    <div class="component">
                        <div class="form-group">
                            <label class="col-md-1 control-label" for="textinput">Vol Schedule :</label>  
                            <div class="col-md-3">
                                <input type="" name="vol" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                          <div class="col-md-12">
                     <div class="component">
                        <div class="form-group">
                         
                            <div class="col-md-1">
                                <input type="" name="satu" class="form-control" placeholder="1">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="dua" class="form-control" placeholder="2">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="tiga" class="form-control" placeholder="3">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="empat" class="form-control" placeholder="4">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="lima" class="form-control" placeholder="5">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="enam" class="form-control" placeholder="6">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="tujuh" class="form-control" placeholder="7">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="delapan" class="form-control" placeholder="8">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="sembilan" class="form-control" placeholder="9">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="sepuluh" class="form-control" placeholder="10">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="sebelas" class="form-control" placeholder="11">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="duabelas" class="form-control" placeholder="12">
                            </div>
                        </div>
                    </div>
                </div>
                                   <div class="col-md-12">
                     <div class="component">
                        <div class="form-group">
                         
                             <div class="col-md-1">
                                <input type="" name="tigabelas" class="form-control" placeholder="13">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="empatbelas" class="form-control" placeholder="14">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="limabelas" class="form-control" placeholder="15">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="enambelas" class="form-control" placeholder="16">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="tujuhbelas" class="form-control" placeholder="17">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="delapanbelas" class="form-control" placeholder="18">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="sembilanbelas" class="form-control" placeholder="19">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="duapuluh" class="form-control" placeholder="20">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="duasatu" class="form-control" placeholder="21">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="duadua" class="form-control" placeholder="22">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="duatiga" class="form-control" placeholder="23">
                            </div>
                             <div class="col-md-1">
                                <input type="" name="duaempat" class="form-control" placeholder="24">
                            </div>
                        </div>
                 
                </div>
                </div>
                <div class="col-md-12 ">
                                          <div class="component">
                        <div class="form-group">
                        <center>
                           <div class="col-md-12 ">
                               <button type="submit" class="btn btn-primary">Simpan <i class="fa fa-save "></i></button>
                            </div>
                       </center>
                   </div>
                        </div>
                    </div>  
                </form>


<?php }
} ?>
