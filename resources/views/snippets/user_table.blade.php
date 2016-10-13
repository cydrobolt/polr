<div>
    <a ng-click="toggleNewUserBox($event)" class="btn btn-primary btn-sm status-display">New</a>
    <div class="new-user-fields">
        <table class="table">
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
            </tr>
            <tr>
                <td><input class="form-control" id="new-user-name"></td>
                <td><input class="form-control" id="new-user-password"></td>
                <td><input class="form-control" id="new-user-email"></td>
                <td>
                    <select class="form-control new-user-role" id="new-user-role">
                        @foreach  ($roles as $role_text => $role_val)
                            <option value="{{$role_val}}">{{$role_text}}</option>
                        @endforeach
                    </select>
                </td>
                <td><a ng-click="addNewUser($event)" class="btn btn-primary btn-sm status-display new-user-add">Add</a></td>
            </tr>
        </table>
        <div id="new-user-status"></div>
    </div>
</div>

<table id="{{$table_id}}" class="table table-hover">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Activated</th>
            <th>API</th>
            <th>Role</th>
            <th>Delete</th>
        </tr>
    </thead>
</table>
