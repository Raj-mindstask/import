<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Bootstrap Example</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <style>
        .bottom {
            margin-bottom: 15px;
        }
    </style>

<body>
    <div class="container">
        <h2 style="text-align: center">Product Mapper</h2>
        <br>
        <form action="{{ route('screen3') }}" method="post">
            @csrf
            <input type="hidden" name="fileName" value="{{$res['fileName']}}">
            <div class="form-inline">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;">
                    <label class="col-sm-2 col-form-label" for="name">System Fields</label>
                    <label class="col-sm-10 col-form-label" for="name">Excel/CSV Import Fields</label>
                </div>
            </div>

            <div class="form-inline">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;">
                    <label class="col-sm-2 col-form-label" for="name">SKU :</label>
                    <select name="map[sku]" class="form-control">
                        @foreach ($res['headers'] as $header)
                            <option value="{{$header}}">{{$header}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-inline">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;">
                    <label class="col-sm-2 col-form-label" for="name">Title :</label>
                    <select name="map[title]" class="form-control">
                        @foreach ($res['headers'] as $header)
                            <option value="{{$header}}">{{$header}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-inline">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;">
                    <label class="col-sm-2 col-form-label" for="name">Description :</label>
                    <select name="map[description]" class="form-control">
                        @foreach ($res['headers'] as $header)
                            <option value="{{$header}}">{{$header}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-inline">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;">
                    <label class="col-sm-2 col-form-label" for="name">Price :</label>
                    <select name="map[price]" class="form-control">
                        @foreach ($res['headers'] as $header)
                            <option value="{{$header}}">{{$header}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-inline">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;">
                    <label class="col-sm-2 col-form-label" for="name">Quantity :</label>
                    <select name="map[quantity]" class="form-control">
                        @foreach ($res['headers'] as $header)
                            <option value="{{$header}}">{{$header}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-inline">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;">
                    <label class="col-sm-2 col-form-label" for="name">Category :</label>
                    <select name="map[category]" class="form-control">
                        @foreach ($res['headers'] as $header)
                            <option value="{{$header}}">{{$header}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <div class="form-inline" style="margin-left:150px;margin-top:30px;">
                <div class="col-md-12 form-group" style="margin-bottom: 15px;" style="margin-top:30px;">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
