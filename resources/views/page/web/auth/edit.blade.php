<form id="form_profile">
    <div class="row col-mb-50 gutter-50">
        <div class="col-lg-6">
            <h3>Personal Information</h3>
            <div class="row mb-0">
                <div class="col-md-6 form-group">
                    <label for="billing-form-name">Name:</label>
                    <input type="text" name="name" value="{{Auth::user()->name}}" class="sm-form-control" />
                </div>
                <div class="col-md-6 form-group">
                    <label for="billing-form-email">Email Address:</label>
                    <input type="email" name="email" value="{{Auth::user()->email}}" class="sm-form-control" />
                </div>
                <div class="w-100"></div>

                <div class="col-md-6 form-group">
                    <label for="billing-form-phone">Phone:</label>
                    <input type="text" name="phone" value="{{Auth::user()->phone}}" class="sm-form-control" />
                </div>
                <div class="col-md-6 form-group">
                    <label for="billing-form-password">Password:</label>
                    <input type="password" name="password" class="sm-form-control" />
                </div>
                <div class="col-12 form-group">
                    <label for="photo">Photo <small>*</small></label>
                    <input type="file" accept="image/*" name="photo">
                </div>

            </div>
        </div>
        <div class="col-lg-6">
            <h3>Address</h3>
            <div class="row mb-0">
                <div class="col-12 form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" value="{{Auth::user()->address}}" class="sm-form-control" />
                </div>

                <div class="col-6 form-group">
                    <label for="province">Province</label>
                    <select class="form-control" name="province" id="province" class="form-control" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinsi as $item)
                            <option value="{{$item->province_id}}"{{$item->province_id==$user->province_id?'selected':''}}>{{$item->province_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 form-group">
                    <label for="city">City / Town</label>
                    <select class="form-control" name="city" id="city" class="form-control" required>
                        <option value="">Pilih Provinsi Terlebih Dahulu</option>
                    </select>                
                </div>
                <div class="col-6 form-group">
                    <label for="subdistrict">Subdistrict</label>
                    <select class="form-control" name="subdistrict" id="subdistrict" class="form-control" required>
                        <option value="">Pilih Kota Terlebih Dahulu</option>
                    </select>                
                </div>
                <div class="col-6 form-group">
                    <label for="postcode">Postcode</label>
                    <input type="text" name="postcode" value="{{Auth::user()->postcode}}" class="sm-form-control" />
                </div>

            </div>
        </div>
        <div class="col-lg-12">
            <button id="tombol_simpan" onclick="handle_upload('#tombol_simpan','#form_profile','{{route('user.auth.update',$user->id)}}','POST','Update')" class="button button-3d float-end">Update</button>
        </div>
    </div>
</form>

<script>
    @if($user->province_id)
    $('#province').val('{{$user->province_id}}');
    setTimeout(function(){ 
        $('#province').trigger('change');
        setTimeout(function(){ 
            $('#city').val('{{$user->city_id}}');
            $('#city').trigger('change');
            setTimeout(function(){ 
                $('#subdistrict').val('{{$user->subdistrict_id}}');
            }, 2000);
        }, 2000);
    }, 1000);
    @endif
    $("#province").change(function(){
            $.ajax({
                type: "POST",
                url: "{{route('user.city.get_list')}}",
                data: {id_province : $("#province").val()},
                success: function(response){
                    $("#city").html(response);
                }
            });
        });
        $("#city").change(function(){
            $.ajax({
                type: "POST",
                url: "{{route('user.subdistrict.get_list')}}",
                data: {id_city : $("#city").val()},
                success: function(response){
                    $("#subdistrict").html(response);
                }
            });
        });
</script>