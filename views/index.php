<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>MDT</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/main.css">
    </head>
    <body>
        <div id='app'>
            <div class="mdt-container">
              <div class="mdt-panel">
                <h2 class='text-center'>Sign in</h2>
                <form  style='padding:10px;'>
                    <div class="d-flex">
                        <div class="form-group" style='padding:0 5px;'>
                            <label>Collar no</label>
                            <input type="text" class="form-control" placeholder="123">
                        </div>
                        <div class="form-group"style='padding:0 5px;'>
                            <label>Name</label>
                            <input type="text" class="form-control" placeholder='J.Doe'>
                        </div>
                    </div>
                    <div class="form-group" >
                        <label>Shift</label>
                        <select type="text" class="form-control">
                            <option value="" selected disabled>Select a server</option>
                            <option>Kirsty test</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="">
                        <label class="form-check-label">
                            I am accompanying another officer
                        </label>
                    </div>
                    <div class="bordered-container">
                        <div class="bordered-container__heading"><h5>Vehicle details</h5></div>
                        <div class="bordered-container__content">
                            <div class="form-group">
                                <label>Callsign</label>
                                <select type="text" class="form-control">
                                    <option value="" selected disabled>Select a callsign</option>
                                    <option>CN20</option>
                                </select>
                                <small class="form-text">The person you are accompanying must have already signed up for you to see the callsign in the list.</small>
                            </div>
                        </div>
                    </div>
                    <div class="bordered-container">
                        <div class="bordered-container__heading"><h5>Vehicle details</h5></div>
                        <div class="bordered-container__content">
                            <div class="form-group">
                                <label>Callsign</label>
                                <input type="text" class="form-control" placeholder="CN20">
                            </div>
                            <div class="form-group" >
                                <label>Department</label>
                                <select type="text" class="form-control">
                                    <option value="" selected disabled>Select a server</option>
                                    <option>Frontline Policing</option>
                                    <option>Crime Squad</option>
                                    <option>Traffic</option>
                                    <option>Trojan</option>
                                    <option>RMU</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </body>
    <script src="/js/main.js" charset="utf-8"></script>
</html>
