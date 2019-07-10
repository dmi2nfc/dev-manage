<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>st13 Bookkeping</title>
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="/css/app.css">

    </head>
    <body>

        <div id="app">
            @include ('layouts.header')
            {{-- <router-link to="/" exact>Home</router-link>
            <router-link to="/programmers">Programmers</router-link> --}}
            <section class="section">
                <router-view></router-view>
            </section>

            <!-- <programmer-list
                v-if="programmers.length"
                v-bind:programmers="programmers">
            </programmer-list>

            <button class="btn btn-primary btn-large"
                v-on:click="addNew"
                v-bind:title="newName">Add New +
            </button>
            <h1 :class="[newName==1 ? 'color-red': 'color-blue']" v-text="computedNewName"></h1> -->
        </div>

        <!-- <div id="one">
            <coupon v-model="coupon"></coupon>
            <h1>@{{ shared.user.name }}</h1>
        </div>

        <div id="two">
            <h1>@{{ shared.user.name }}</h1>
        </div> -->

         <!-- <script src="{{ asset('js/app.js') }}"></script> -->
         <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
