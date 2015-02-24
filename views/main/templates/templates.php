<!-- BEGIN ERROR PAGE TEMPLATE -->
     <script type="text/template" id="error-page-template">
          <h1>О-па!</h1>
          <p><%= message %></p>
          <a class="btn btn-primary btn-link" href="#/dashboard">На главную</a>
     </script>
<!-- END ERROR PAGE TEMPLATE -->

<!-- BEGIN DASHBOARD PAGE TEMPLATE -->
     <script type="text/template" id="dashboard-template">

          <div id="dashboard" class="tabbed-page">
               <h1>Меню</h1>

               <ul class="nav nav-tabs" id="dashboard-tabs">
                    <li class="tab-1 active"><a href="#/tab-1">Основное</a></li>
                    <li class="tab-2"><a href="#/tab-2">Что-то еще</a></li>
                    <li class="tab-3"><a href="#/tab-3">Дополнительно</a></li>
               </ul>

               <div id="tab-1" class="tab-1 tab-container container active empty">

               </div>

               <div id="tab-2" class="tab-2 tab-container container empty">

               </div>

               <div id="tab-3" class="tab-3 tab-container container empty">

               </div>
          </div>

     </script>
<!-- END DASHBOARD PAGE TEMPLATE -->

<!-- BEGIN TAB-1 TEMPLATE -->
     <script type="text/template" id="tab-1-template">

     <div class="container tab-1">

     </div>

     </script>
<!-- END TAB-1 TEMPLATE -->

<!-- BEGIN TAB-2 TEMPLATE -->
     <script type="text/template" id="tab-2-template">

     <div class="container tab-2">

     </div>

     </script>
<!-- END TAB-2 TEMPLATE -->

<!-- BEGIN TAB-3 TEMPLATE -->
     <script type="text/template" id="tab-3-template">

     <div class="container tab-3">

     </div>

     </script>
<!-- END TAB-3 TEMPLATE -->

<!-- BEGIN ALERT TEMPLATE -->
<script type="text/template" id="alert-template">
     <div class="alert">
          <h1><%= title %></h1>
          <% if (typeof(message) != 'undefined') { %>
          <p><%= message %></p>
          <% } %>
          <p><button id="close-alert" class="btn btn-primary">OK</button></p>
     </div>
</script>
<!-- END ALERT TEMPLATE -->