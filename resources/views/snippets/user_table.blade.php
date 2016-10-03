<div style="margin-bottom: 30px;">
    <a ng-click="toggleNewUserBox($event)" id="add_user_btn" class='btn btn-primary btn-sm status-display'>Add New User</a>
    <div id="add_user_box" style="display: none; margin-top: 10px;">
        <table class="table">
            <tr>
                <th width="20%">Username</th>
                <th width="20%">Password</th>
                <th width="20%">Email</th>
                <th width="10%">Role</th>
                <th width="10%"></th>
            </tr>
            <tr>
                <td><input id="new_user_name" /></td>
                <td><input id="new_user_password" /></td>
                <td><input id="new_user_email" /></td>
                <td>
                    <select id="new_user_role" style="width: 100%; height: 85%;">
                        @foreach  ($roles as $role_text => $role_val)
                            <option value="{{$role_val}}">{{$role_text}}</option>
                        @endforeach
                    </select>
                </td>
                <td><a ng-click="addNewUser($event)" class='btn btn-primary btn-sm status-display' style="padding-left: 15px; padding-right: 15px;">Add</a></td>
            </tr>
        </table>
        <div id="new_user_status" style="text-align: center;"></div>
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
