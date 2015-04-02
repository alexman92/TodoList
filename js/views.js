/** 
 * Backbone views
 * Author: alexman92
 */

/**
 *  View of main page
 */
App.Views.MainView = Backbone.View.extend({
    el: '#div-page-content',
    events: {
        "click #btn-enter" : "enter",
        "click #btn-reg": "register"
    },
    initialize: function() {
        
        this.template = template('tmpl-main');
        this.render();
    },
    
    render: function() {
        this.$el.html( this.template() );
        return this;
    }, 
    
    enter: function() {
        
        var enter_params = $('#form-enter').serializeArray();
       
        var name = $('#form-enter').find('input[name=email]').val();
        var pass = $('#form-enter').find('input[name=password]').val();
        
        if( (name === '') || (pass.trim() === '') ) {
            alert("Please enter all data to login");
            return false;
        }
        
        if(!this.validateEmail(name)) {
            alert("Please enter correct email");
            return false;
        }
        $.getJSON( 
                'core/core.php',
                enter_params,
                function(data, status, ajax){
                    if(data.result === 'success') {
                        new App.Models.UserModel({login: data.login, sid: data.sid});
                        if( (data.lists !== undefined) && (data.lists !== '') ) {
                            for(var i in data.lists) {
                                new App.Views.TodoView({lists: data.lists[i], name: i});
                            }
                        }
                    } else {
                        alert(data.description);
                    }
                }
        );

        this.loadLists();
    }, 
    
    register: function() {
        
        var reg_params = $('#form-reg').serializeArray();
        
        var name = $('#form-reg').find('input[name=email]').val();
        var pass = $('#form-reg').find('input[name=password]').val();
        var conf_pass = $('#form-reg').find('input[name=conf-password]').val();
        
        if( (name === '') || (pass.trim() === '') ) {
            alert("Please enter all data to login");
            return false;
        }
        
        if(!this.validateEmail(name)) {
            alert("Please enter correct email");
            return false;
        }
        
        if(pass !== conf_pass) {
            alert("Your password and confirm password are not equal");
            return false;
        }
        $.getJSON( 
                'core/core.php',
                reg_params,
                function(data, status, ajax){
                    if(data.result === 'success') {
                        alert('Registration complete!');
                        $('#form-reg').find('input[name=email]').val('');
                        $('#form-reg').find('input[name=password]').val('');
                        $('#form-reg').find('input[name=conf-password]').val('');
                    } else {
                         alert(data.description);
                    }
                }
        );
    }, 
    
    validateEmail: function (email) {
        var re = /[^\s@]+@[^\s@]+\.[^\s@]+/;
        return re.test(email);
    },
    
    loadLists: function() {
        
    }
});

/**
 * View of todos' page
 */
App.Views.TodoPageView = Backbone.View.extend({
    el: '#div-page-content',
    events: {
        'click #but-add-todo': 'addTodoList',
        'click #but-logout': 'logout'
    },
    
    initialize: function() {
        this.template = template('tmpl-todopage');
        this.render();
    }, 
    
    render: function() {
        this.$el.html( this.template( this.model.toJSON() ) );
        return this;
    },
    
    addTodoList: function() {
        new App.Views.TodoView();
    },
    
    logout: function() {
        setLocalData('sid','');
        setLocalData('login', '');
        window.location.reload();
    }
});

/**
 * Todo view
 */

App.Views.TodoView = Backbone.View.extend({
    className: 'inline',
    events: {
        "click .edit-list": "editTitle",
        "click .remove-list": "removeList",
        "click .save-list": "saveTitle",
        "click .add-task": "addTask",
        "click .save-todo": "saveTodoList"
    },
    
    initialize: function(options) {
        if(options !== undefined) {
            this.name = options.name;
            this.lists = options.lists;
        } else {
            this.name = 'Unnamed';
        }
        this.template = template('tmpl-todo');
        this.render();

    }, 
    
    render: function() {
        this.$el.html( this.template() );
        if(this.lists !== undefined) {this.loadTask();}
        $('#div-todos').prepend(this.$el);
        return this;
    }, 
    
    editTitle: function() {
        var $span = this.$el.find('.span-title-list'),
        $input = this.$el.find('.inp-title-list'),
        $editbut = this.$el.find('.edit-list'),
        $savebut = this.$el.find('.save-list');
        
        
        
        $span.hide();
        $input.val($span.text()).show();
        $editbut.hide();
        $savebut.show();
    }, 
    
    removeList: function() {
        if(!confirm('Are you sure want to delete list?')) {
            return false;
        }
        var that = this;
        $.post( 
                'core/core.php',
                {
                    'action': 'remove_list',
                    'login': getLocalData('login'),
                    'listname':  this.$el.find('.span-title-list').text()
                },
                function(data, status, ajax){
                    data = JSON.parse(data);
                    if(data.result === 'success') {
                        that.$el.remove();
                        alert('List successfully removed!');
                    } else {
                         alert(data.description);
                    }
                }
        );
        
    }, 
    
    saveTitle: function() {
        var $span = this.$el.find('.span-title-list'),
        $input = this.$el.find('.inp-title-list'),
        $editbut = this.$el.find('.edit-list'),
        $savebut = this.$el.find('.save-list');

        if($input.val().trim() === '') {
            alert("Incorrect todolost's name");
            return false;
        }
        
        $input.hide();
        $span.text($input.val()).show();
        $savebut.hide();
        $editbut.show();
    },
    
    addTask: function() {
        var name = this.$el.find('.add-task-name').val();
        
        if(name.trim() === '') {
            alert("Task name is empty"); return false;
        }
        
        this.$el.find('.add-task-name').val('');
        new App.Views.TodoTaskView(this.$el.find('tbody'), name);
    },
    
    loadTask: function() { 
        for(var i in this.lists) {
   
                new App.Views.TodoTaskView(this.$el.find('tbody'), this.lists[i].taskname, this.lists[i].done);
        }
    },
    
    saveTodoList: function() {
        if(!confirm('Are you sure want to save list?')) {
            return false;
        }
        var saved_data = {};
        
        saved_data['tasks'] = [];
        
        saved_data['title'] = this.$el.find('.span-title-list').text();
        saved_data['login'] = getLocalData('login');
        saved_data['action'] = 'save_list';
        this.$el.find('tbody').children().each(function(index, elem){
            var taskname = $(elem).find('.span-title-task').text();
            
            saved_data['tasks'].push({ 'taskname': $(elem).find('.span-title-task').text(), 'done': $(elem).hasClass('bg-done')}); 
        });
               
        $.post( 
                'core/core.php',
                saved_data,
                function(data, status, ajax){
                    data = JSON.parse(data);
                    if(data.result === 'success') {
                        alert('List successfully saved!');
                    } else {
                         alert(data.description);
                    }
                }
        );
        
    }
});

App.Views.TodoTaskView = Backbone.View.extend({
    tagName: "tr", 
    events: {
        "click .edit-task": "editTask",
        "click .remove-task": "removeTask",
        "click .save-task": "saveTask",
        "click .up": "moveUp",
        "click .down": "moveDown",
        "click .done": "markDone"
    },
    
    initialize: function($parent, name, done) {
        this.parent = $parent;
  
        this.name = name;
        if(done !== undefined) {
            this.done = done == '0' ? false : true;
        }
        this.template = template('tmpl-todotask');
        this.render();
    },
    
    render: function() {
        this.$el.html(this.template());
        this.parent.append(this.$el);
        if(this.done) {
            this.$el.find('.span-title-task').addClass('del');
            this.$el.addClass('bg-done');
        }
        return this;
    }, 
    
    editTask: function() {
        var $span = this.$el.find('.span-title-task'),
        $input = this.$el.find('.inp-title-task'),
        $editbut = this.$el.find('.edit-task'),
        $savebut = this.$el.find('.save-task'),
        $arrows = this.$el.find('.arrows');
        
        $span.hide();
        $input.val($span.text()).show();
        $editbut.hide();
        $arrows.hide();
        $savebut.show();
    },
    
    removeTask: function() {
        this.$el.remove();
    },
    
    saveTask: function() {
        var $span = this.$el.find('.span-title-task'),
        $input = this.$el.find('.inp-title-task'),
        $editbut = this.$el.find('.edit-task'),
        $savebut = this.$el.find('.save-task'),
        $arrows = this.$el.find('.arrows');
        
        
        $input.hide();
        $span.text($input.val()).show();
        $savebut.hide();
        $editbut.show();
        $arrows.show();
    },
    
    moveUp: function() {
        var row = this.$el;
        row.insertBefore(row.prev());
    },
    
    moveDown: function() {
        var row = this.$el;
        row.insertAfter(row.next());
    },
    
    markDone: function(e) {
        var is_checked = e.currentTarget.checked;
        
        if(is_checked) {
            this.$el.find('.span-title-task').addClass('del');
            this.$el.addClass('bg-done');
        } else {
            this.$el.find('.span-title-task').removeClass('del');
            this.$el.removeClass('bg-done');
        }
    }
});
