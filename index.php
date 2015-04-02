<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Todo List</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="css/bootstrap.css"/>
        <link rel="stylesheet" href="css/main.css"/>
        

    </head>
    <body class="bg-success">
        <div id="div-page-content">
        </div>
        <script type="text/template" id="tmpl-main">
            <div class="navbar navbar-static-top" style=" height: 100%; border-bottom: 2px solid black">
                <div class="navbar-inner" style="margin: 10px;">
                    
                    <p  style="position: absolute; margin-top: 0px; font-size: 18pt"><span style="top: 5px;"class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;Todo List</p>
              
                    <form id="form-enter" class="form-inline  text-right">
                        <input type="hidden" name="action" value="enter"/>
                        <div class="form-group">
                            <label for="email">Email adress: </label>
                            <input type="email" required name="email" class="form-control" id="inp-email" placeholder="Enter email"/>
                        </div>
                        <div class="form-group">
                            <label for="password">Password: </label>
                            <input type="password" required name="password" class="form-control" id="inp-password" placeholder="Password"/>
                        </div>
                        <button type="button" class="btn btn-default" id="btn-enter">Enter</button>
                    </form>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-8">

                        <form id="form-reg" style="margin-top: 50px;">
                            <h3 class="text-center">Registration</h3>
                            <input type="hidden" name="action" value="register"/>
                            <div class="form-group">
                                <label for="email">Email adress: </label>
                                <input type="email" required name="email" class="form-control" id="inp-email" placeholder="Enter email"/>
                            </div>
                            <div class="form-group">
                                <label for="password">Password: </label>
                                <input type="password" required name="password" class="form-control" id="inp-pass" placeholder="Password"/>
                            </div>
                            <div class="form-group">
                                <label for="conf-password">Confirm Password: </label>
                                <input type="password" required name="conf-password" class="form-control" id="inp-conf-pass" placeholder="Confirm password"/>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-default btn-lg" id="btn-reg">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </script>
 
        <script type="text/template" id="tmpl-todopage">
            <div class="navbar navbar-static-top" style=" height: 100%; border-bottom: 2px solid white">
                <div class="navbar-inner" style="margin: 10px;">
                    
                    <p  style="position: absolute; margin-top: 0px; font-size: 18pt"><span style="top: 5px;"class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;Todo List</p>
                    
                    <div class="text-right">
                    <span>You are logged as <strong><%= this.model.get('login') %><strong></span>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row" id="div-todos">
                    <div class="text-center">
                        <button class="btn btn-primary btn-lg text-right" id="but-add-todo" style="margin: 10px"><span class="glyphicon glyphicon-plus"></span> Add TODO list </button>
                    </div>
                </div>
            </div>
                
            </div>
        </script>
        
        <script type="text/template" id="tmpl-todo">
            <div class="white-bg" style="display: inline-block; box-shadow: 0px 3px 2px #aab2bd; width: 350px; padding: 10px; margin: 10px;">    

                <table style="width: 100%; cursor: pointer" class="table table-hover">
                    <thead>
                        <tr style="background-color: lightblue">
                            <th colspan="2" class="text-left">
                                <span class="span-title-list"><%= this.name %></span>
                                <input type="text" style="display: none; width: 100%" class="inp-title-list"/>
                            </th>
                            <th class="text-right">
                                <button class="btn btn-xs edit-list"><span class="glyphicon glyphicon-pencil"></span></button>
                                <button class="btn btn-xs save-todo"><span class="glyphicon glyphicon-floppy-disk"></span></button>
                                <button class="btn btn-xs save-list" style="display: none"><span class="glyphicon  glyphicon-ok"></span></button>
                                <button class="btn btn-xs remove-list"><span class="glyphicon glyphicon-remove"></span></button>
                            </th>
                        </tr>
                        <tr style="background-color: lightgray">
                            <th colspan="3">
                                <div class="input-group" style="width: 100%">
                                    <input type="text"  class="form-control add-task-name" placeholder="Add task">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success add-task" type="button">Add task!</button>
                                    </span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
              
                    </tbody>
                </table>
            </div>
        </script>
        
        <script type="text/template" id="tmpl-todotask">
            
                <td style="width: 10px;">
                    <input type="checkbox" class="done" <% if(this.done){%> checked="checked" <% } %>/>
                </td>
                <td class="text-left">
                    <div>
                        <span class="span-title-task"><%= this.name %></span>
                        <input type="text" style="display: none;" class="inp-title-task"/>
                    </div>
                </td>
                <td class="text-right">
                    <div>
                        <button class="btn btn-xs arrows up"><span class="glyphicon glyphicon-menu-up"></span></button>
                        <button class="btn btn-xs arrows down"><span class="glyphicon glyphicon-menu-down"></span></button>
                        <button class="btn btn-xs edit-task"><span class="glyphicon glyphicon-pencil "></span></button>
                        <button class="btn btn-xs save-task" style="display: none"><span class="glyphicon  glyphicon-ok"></span></button>
                        <button class="btn btn-xs remove-task"><span class="glyphicon glyphicon-remove "></span></button>
                    </div>
                </td>
          
        </script>
        
        <!-- frameworks -->
        <script src="libs/jquery-1.11.2.js"></script>
        <script src="libs/underscore.js"></script>
        <script src="libs/backbone.js"></script>
        <script src="libs/bootstrap.js"></script>
        
        <!-- user scripts -->
        <script src="js/globals.js"></script>
        <script src="js/models.js"></script>
        <script src="js/views.js"></script>
        <script src="js/controllers.js"></script>
        <script src="js/core.js"></script>
    </body>
</html>
