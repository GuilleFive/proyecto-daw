<div class="dropdown d-flex justify-content-center">
    <button class="bg-transparent border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
            aria-expanded="false">
        <i class="fa fa-caret-square-down blue-color"></i>
    </button>
    <ul class="dropdown-menu dark-background" aria-labelledby="dropdownMenuButton1">
        <li>
            <button class="btn btn-primary button-primary-dark btn-sm">
                <i class="fa fa-eye"></i>
            </button>
        </li>
        @if(!$user->trashed())
            @can('create_admins')
                <li>
                    <button data-user="{{json_encode($user)}}"
                            class="btn @if($user->hasRole('client')) btn-success @else btn-warning @endif btn-sm users-change-btn">
                        <i class="fa @if($user->hasRole('client')) fa-angle-double-up @else fa-angle-double-down @endif"></i>
                    </button>
                </li>
            @endcan
        @else
            <li>
                <button data-user="{{json_encode($user)}}" class="btn btn-sm btn-danger users-force-delete-btn">
                    <i class="fa fa-times "></i>
                </button>
            </li>
        @endif
        <li>
            <button data-user="{{json_encode($user)}}"
                    class="btn btn-sm @if(!$user->trashed())btn-danger users-delete-btn @else btn-success users-restore-btn @endif">
                <i class="fa @if(!$user->trashed()) fa-trash @else fa-trash-restore @endif"></i>
            </button>
        </li>
    </ul>
</div>



