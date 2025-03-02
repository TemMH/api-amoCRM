<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css' , 'resources/js/app.js'])

</head>

<body>

    <div class="login-root">
        <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">

            <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">

                <div class="formbg-outer">
                    <div class="formbg">
                        <div class="formbg-inner padding-horizontal--48">
                            <form id="stripe-login" action="{{ route('form.submit') }}" method="POST">
                                @csrf
                                
                                <div class="field padding-bottom--24">
                                    <label for="name">Имя</label>
                                    <input type="text" name="name" id="name" required>
                                </div>

                                <div class="field padding-bottom--24">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" required>
                                </div>

                                <div class="field padding-bottom--24">
                                    <label for="phone">Телефон</label>
                                    <input type="phone" name="phone" id="phone" required>
                                </div>

                                <div class="field padding-bottom--24">
                                    <div class="grid--50-50">
                                        <label for="price">Цена</label>
                                    </div>
                                    <input type="number " name="price" min="0" id="price" required>
                                </div>

                                <div class="field">
                                    <input type="submit" name="submit" value="Continue">
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="footer-link">
                        <div class="listing padding-top--24 padding-bottom--24 flex-flex center-center">
                            <span>
                                <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                                </p>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</html>