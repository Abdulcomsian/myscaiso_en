<?php

namespace App\Http\Controllers;
use App\AccidentRisk;
use App\AddUsers;
use App\Assessment;
use App\Audit;
use App\calibration;
use App\customer_review;
use App\customers;
use App\Employee;
use App\EmployeeTraning;
use App\EmpSkills;
use App\Helpers\HelperFunctions;
use App\Maintain_rec;
use App\Mgtreview;
use App\Nonconform;
use App\Qmsaudit;
use App\requirement;
use App\SendNotifications;
use App\Supplier;
use App\Workinstructions;
use App\User;
use App\LoginHistoryUser;
use App\CustomManual;
use Carbon\Carbon;
use App\Certificate;
use App\UserDownload;
use App\UserNotesHistory;
use App\Download;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Notification;
use App\Notifications\messageNotification;
use App\Chemical;
use Illuminate\Support\Facades\Schema;

class AddUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index(Request $request)
     {
         // Get the 'showusers' query parameter from the URL
         $showUsers = $request->query('showusers');
         // Check if the 'showusers' parameter is present and not null
         if (!is_null($showUsers)) {
             // Example logic based on the value of 'showusers'
             if ($showUsers == '1') {
                 // Fetch SCAISO Users
                 $users = AddUsers::where('role_type', 'user')->where('user_type', '1')->orderBy('id', 'desc')->get();
             }
             elseif ($showUsers == '2') {
                // Fetch SCAISO Users
                $users = AddUsers::where('role_type', 'user')->where('user_type', '2')->orderBy('id', 'desc')->get();
            } else {
                 // Fetch All Users
                 $users = AddUsers::where('role_type', 'user')->orderBy('id', 'desc')->get();
             }
             // Return the filtered data to the view
             return view('admin.dashboard.admin.view_user', compact('users'));
         } else {
             // Default case, fetch 'user' role type
             $users = AddUsers::where('role_type', 'user')->orderBy('id', 'desc')->get();
             // Return the default data to the view
             return view('admin.dashboard.admin.view_user', compact('users'));
         }
     }
     


    // Check Login History of User 

    // public function userLoginHistory(Request $request)
    // {
    //     $user_id = $request->input('user_id');
        
    //     $loginHistory = LoginHistoryUser::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        
    //     // Start building the table
    //     $list = '<table class="table">
    //     <thead>
    //         <tr>
    //             <th>ID</th>
    //             <th>Login Time</th>
    //         </tr>
    //     </thead>
    //     <tbody>';
        
    //     $i = 1;
        
    //     foreach ($loginHistory as $history) {
    //         $list .= '<tr>';
    //         $list .= '<td style="text-align: center;">' . $i . '</td>';
    //         $list .= '<td style="padding:5px 15px; text-align: center;">' . date('d-m-Y H:i:s', strtotime($history->login_time)) . '</td>';
    //         $list .= '</tr>';
    //         $i++;
    //     }
        
    //     // Close the table
    //     $list .= '</tbody>
    //     </table>';
        
    //     return $list;
    // }



    
    // working code 
    public function userLoginHistory(Request $request)
    {
        $user_id = $request->input('user_id');
        
        $loginHistory = LoginHistoryUser::where('user_id', $user_id)->orderBy('id', 'desc')->get();
        // dd($loginHistory);
        
        $list = '<table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Login Date & Time</th>
                <th>IP Address</th>
                <th>Browser</th>
            </tr>
        </thead>
        <tbody>';
        
        $i = 1;
        
        foreach ($loginHistory as $history) 
        {
            $list .= '<tr>';
            $list .= '<td style="text-align: center;">' . $i . '</td>';
            $list .= '<td style="padding:5px 15px; text-align: center;">' . date('d-m-Y H:i:s', strtotime($history->login_time)) . '</td>';
            $list .= '<td style="padding:5px 15px; text-align: center;">' . $history->ip_address . '</td>';
            $list .= '<td style="padding:5px 15px; text-align: center;">' . $history->browser . '</td>';
            $list .= '</tr>';
            $i++;
        }
        
        $list .= '</tbody>
        </table>';
        
        return $list;
    }
      public function userNoteshistory(Request $request)
    {
        $user_id = $request->input('user_id');
        
        $notesHistory = UserNotesHistory::where('company_id', $user_id)->orderBy('id', 'desc')->get();
        //dd($notesHistory);
        
        // $list = '<table class="table">
        //     <thead>
        //         <tr>
        //             <th>Note</th>
        //             <th></th>
        //             <th>Date & Time</th>
        //             <th colspan="2">Action</th>
        //         </tr>
        //     </thead>
        //     <tbody>';
       $list = '<table class="table">
            <thead>
                <tr>
                    <th>Note</th>
                    <th></th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>';     

        $i = 1;

        foreach ($notesHistory as $nhistory) {
            $list .= '<tr id="note-row-' . $nhistory->id . '">';
            $list .= '<td style="text-align: center;">' . $i . '</td>';
        $list .= '<td style="padding:5px 15px; text-align: left;" id="note-text-' . $nhistory->id . '">' .
                    nl2br($nhistory->note);
        
        if ($nhistory->note_img) {
            $filePath = asset('public/uploads/notes/' . $nhistory->note_img);
            $list .= '<br><a href="' . $filePath . '" target="_blank">View Attachment</a>';
        } 

        $list .= '</td>';
            $list .= '<td style="padding:5px 15px; text-align: center;">' . date('d-m-Y H:i:s', strtotime($nhistory->dated)) . '</td>';
            // $list .= '<td style="text-align: center;">
            //     <button onclick="editNote(' . $nhistory->id . ', \'' . addslashes($nhistory->note) . '\')" class="btn btn-sm btn-clean" title="Edit"><i class="fa fa-edit"></i></button>
            //     <button onclick="deleteNote(' . $nhistory->id . ')" class="btn btn-sm btn-clean" title="Delete"><i class="fa fa-trash"></i></button>
            // </td>';
            $list .= '</tr>';
            $i++;
        }

        $list .= '</tbody>
        </table>';

        return $list;
 }
    public function userDownloadHistory(Request $request)
    {
        $user_id = $request->input('user_id');
        $users = User::with('userDownload', 'userDownload.downloads')->where('id', $user_id)->first();
       // $loginHistory = LoginHistoryUser::where('user_id', $user_id)->orderBy('id', 'desc')->get();   
        
        $list = '<table class="table" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                 <th>Description</th>
                <th>Downloaded File</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';
        
        $i = 1;
        if(isset($users->userDownload)){
            foreach ($users->userDownload as $ud)
            {
                $list .= '<tr>';
                $list .= '<td style="text-align: center;">' . $i . '</td>';
                $list .= '<td style="padding:5px 15px; text-align: center;"><h5>' . $ud->downloads->name ?? ''  . '</h5></td>';
                $list .= '<td style="padding:5px 15px; text-align: center;">' . $ud->downloads->des ?? ''  . '</td>';
                $list .= '<td style="padding:5px 15px; text-align: center;"><a class="btn-fetch-data" href="'.asset('uploads/downloads/'. $ud->downloads->download_file).'" data-id="'.$ud->downloads->id.'" target="_blank"> Download ' . $ud->downloads->name ?? '' . '</a></td>';
                $list .= '<td style="padding:5px 15px; text-align: center;">' . $ud->dated ?? '' . '</td>';
                $list .= '</tr>';
                $i++;
            }
        }   
        
        
        $list .= '</tbody>
        </table>';
        
        return $list;

    }
    public function userEmailDetails(Request $request){
        $itemID = $request->input('user_id');
        // dd($itemID);
        $details = SendNotifications::where('send_to', '=', $itemID)
        // ->where(function($query) {
        //     $query->where('total_days', '=', 90)
        //         ->orWhere('total_days', '=', 180)
        //         ->orWhere('total_days', '=', 300);
        // })
        ->where('total_days', '!=', null)
        ->get();


        $list = '<table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';


        $i = 1;
        foreach($details as $detail){
            $list .= '<tr>';
            $list .= '<td style="text-align: center;">' . $i . '</td>';
            if ($detail->total_days >= 90 && $detail->total_days < 180) {
                $list .= '<td style="padding:5px 10px; text-align: center;">Three Months Notification has been Sent</td>';
            } elseif ($detail->total_days >= 180 && $detail->total_days < 300) {
                $list .= '<td style="padding:5px 10px; text-align: center;">Six Months Notification has been Sent</td>';
            } elseif ($detail->total_days >= 300) {
                $list .= '<td style="padding:5px 10px; text-align: center;">Ten Months Notification has been Sent</td>';
            }
            $list .= '<td style="padding:5px 10px; text-align: center;">' . date('d-m-Y H:i:s', strtotime($detail->created_at)) . '</td>';
            $list .= '</tr>';
            $i++;
        }

        $list .= '</tbody>
        </table>';
        return response()->json(['list' => $list]);
    }


    // public function userLoginHistory(Request $request) 
    // {
    //     // dd("abc");
    //     $user_id = $request->input('user_id');
    //     $page = $request->input('page', 1);
    //     $perPage = 10;
    //     $offset = ($page - 1) * $perPage;
        
    //     $loginHistory = LoginHistoryUser::where('user_id', $user_id)
    //         ->orderBy('id', 'desc')
    //         ->skip($offset)
    //         ->take($perPage)
    //         ->get();
        
    //     $list = '<table class="table">
    //     <thead>
    //         <tr>
    //             <th>ID</th>
    //             <th>Login Date & Time</th>
    //             <th>IP Address</th>
    //             <th>Browser</th>
    //         </tr>
    //     </thead>
    //     <tbody>';
        
    //     $i = 1;
        
    //     foreach ($loginHistory as $history) 
    //     {
    //         $list .= '<tr>';
    //         $list .= '<td style="text-align: center;">' . $i . '</td>';
    //         $list .= '<td style="padding:5px 15px; text-align: center;">' . date('d-m-Y H:i:s', strtotime($history->login_time)) . '</td>';
    //         $list .= '<td style="padding:5px 15px; text-align: center;">' . $history->ip_address . '</td>';
    //         $list .= '<td style="padding:5px 15px; text-align: center;">' . $history->browser . '</td>';
    //         $list .= '</tr>';
    //         $i++;
    //     }
        
    //     $list .= '</tbody>
    //     </table>';
        
    //     $pagination = $loginHistory->links()->toHtml();
    //     return response()->json(['data' => $list, 'pagination' => $pagination]);
    // }


//     public function userLoginHistory(Request $request) 
// {
//     $user_id = $request->input('user_id');
//     $perPage = 10;
    
//     $loginHistory = LoginHistoryUser::where('user_id', $user_id)
//         ->orderBy('id', 'desc')
//         ->paginate($perPage);

//     return view('admin.dashboard.admin.view_user', compact('loginHistory'));
// }





    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
    {
 //dd($request->all());
// dd((date("Y-m-d" , strtotime($request->iso9001_expirydate))));
        try {
            //currdisabl
            $this->validate($request, [
                'name' => 'required',
                'password' => 'required',
                'email' => 'required'
            ]);
            $pass = $request->input('password');
            $addusers = new AddUsers();
            if ($request->file('user_image')) 
            {
                $imagePath = $request->file('user_image');
                $imageName = uniqid() . "." . $request->file('user_image')->extension();

                $path = $request->file('user_image')->storeAs('/uploads/user', $imageName, 'public');
                $request->file('user_image')->move(public_path('/uploads/user'), $imageName);

                $addusers->profile_image = $path;

            }
            $addusers->persone_iso = $request->input('person_iso');
            $addusers->contact_number_iso = $request->input('contact_iso');
            $addusers->emailaddress_iso = $request->input('email_iso');

            /*$this->validate($request, [
                'iso9001_certificate' => 'required',
                'filenames.*' => 'mimes:.pdf'
            ]);
            $this->validate($request, [
                'iso14001_certificate' => 'required',
                'filenames.*' => 'mimes:.pdf'
            ]);
            $this->validate($request, [
                'iso45001_certificate' => 'required',
                'filenames.*' => 'mimes:.pdf'
            ]);*/


            if ($request->file('iso9001_certificate')) 
            {
               
                $file_path = $request->file('iso9001_certificate');
                $file_name = uniqid() . "." . $request->file('iso9001_certificate')->extension();

                $path = $request->file('iso9001_certificate')->storeAs('/uploads/user', $file_name, 'public');
                $request->file('iso9001_certificate')->move(public_path('/uploads/user'), $file_name);
                $addusers->iso9001_certificate = $path;

                $addusers->iso9001_expirydate = date("Y-m-d" , strtotime($request->iso9001_expirydate));

                $addusers->iso9001_description = $request->input('iso9001_description');
            }

            if ($request->file('iso14001_certificate')) 
            {

                $iso14001_certificate_path = $request->file('iso14001_certificate');
                $iso14001_certificate_name = uniqid() . "." . $request->file('iso14001_certificate')->extension();

                $iso14001_certificate_path = $request->file('iso14001_certificate')->storeAs('/uploads/user', $iso14001_certificate_name, 'public');
                $request->file('iso14001_certificate')->move(public_path('/uploads/user'), $iso14001_certificate_name);
                $addusers->iso14001_certificate = $iso14001_certificate_path;

                $addusers->iso14001_expirydate = date("Y-m-d" , strtotime($request->iso14001_expirydate));

                $addusers->iso14001_description = $request->input('iso14001_description');
            }

            if ($request->file('iso45001_certificate')) 
            {

                $iso45001_certificate_path = $request->file('iso45001_certificate');
                $iso45001_certificate_name = uniqid() . "." . $request->file('iso45001_certificate')->extension();

                $iso45001_certificate_path = $request->file('iso45001_certificate')->storeAs('/uploads/user', $iso45001_certificate_name, 'public');
                $request->file('iso45001_certificate')->move(public_path('/uploads/user'), $iso45001_certificate_name);
                $addusers->iso45001_certificate = $iso45001_certificate_path;

                $addusers->iso45001_expirydate = date("Y-m-d" , strtotime($request->iso45001_expirydate));

                $addusers->iso45001_description = $request->input('iso45001_description');
            }
            if (Schema::hasColumns('users', ['audit_report'])) 
            {
                if ($request->file('audit_report')) {

                    $audit_report_path = $request->file('audit_report');
                    $audit_report_name = uniqid() . "." . $request->file('audit_report')->extension();

                    $audit_report_path = $request->file('audit_report')->storeAs('/uploads/user/audit_report', $audit_report_name, 'public');
                    $request->file('audit_report')->move(public_path('/uploads/user/audit_report'), $audit_report_name);
                    $addusers->audit_report = $audit_report_path;
                }
            }
             $addusers->audit_comment=$request->input('audit_comment');

            //$addusers->expiry_date=$request->input('expiry_date');
            $addusers->country = $request->input('country');


            $addusers->name = $request->input('name');
            $addusers->email = $request->input('email');
            $addusers->password = Hash::make($pass);
            $addusers->phone = $request->input('phone');
            $addusers->phonecode = $request->input('phonecode');
            if ($request->input('phoneflag') == "preferred") {
                $phoneflag = "us";
            } else {
                $phoneflag = $request->input('phoneflag');
            }
            // echo $phoneflag;exit;
            $addusers->phoneflag = $phoneflag;
            // $addusers->phoneflag=$request->input('phoneflag');
            $addusers->iso_phone_code = $request->input('isophonecode');
            if ($request->input('isophoneflag') == "preferred") {
                $isophoneflag = "us";
            } else {
                $isophoneflag = $request->input('isophoneflag');
            }
            // echo $phoneflag;exit;
            $addusers->iso_phone_flag = $isophoneflag;
            // $addusers->iso_phone_flag=$request->input('isophoneflag');
            $addusers->director = $request->input('director');
            $addusers->sales_process = $request->input('sales_process');
            //    $addusers->company_profile=$request->input('company_profile');
            if ($request->file('company_profile')) {
                $imagePath = $request->file('company_profile');
                $imageName = uniqid() . "." . $request->file('company_profile')->extension();

                $path = $request->file('company_profile')->storeAs('/uploads/user', $imageName, 'public');
                $request->file('company_profile')->move(public_path('/uploads/user'), $imageName);

                $addusers->company_profile = $path;

            }
            // if($request->input('scaiso')!=''){
            //     $scaiso=1;

            // }
            // else{
            //     $scaiso=0;
            // }
            // if($request->input('adek_school')!=''){
            //     $adekschool=1;

            // }
            // else{
            //     $adekschool=0;
            // }
            //dd($scaiso);
            $addusers->company_name = $request->input('company_name');
            $addusers->company_address = $request->input('company_address');
            $addusers->role_type = 'user';
            $addusers->purchasing_process = $request->input('purchasing_process');
            $addusers->servicing_process = $request->input('servicing_process');
            $addusers->competency_process = $request->input('competency_process');
            $addusers->scope = $request->input('scope');
            $addusers->order_number = $request->input('order_number');
            $addusers->Company_overview = $request->input('Company_overview');
            $current = Carbon::now();
            $expiry = $current->addYears(3);
            $addusers->expiry_date = $expiry;
            $addusers->user_type = $request->input('user_type');
            //$addusers->member_scaiso = $scaiso;
            //$addusers->adek_school = $adekschool;
           

            $addusers->save();
            return redirect('/add_user')->with("Success", "User added Successfully.");
        } catch (Exception $exc) { dd($exc);
            return redirect('/add_user')->with("Error", "Error saving data, please try again.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AddUsers  $addUsers
     * @return \Illuminate\Http\Response
     */
    public function show(AddUsers $addUsers)
    {
        //
    }
    public function userDetails($request)
    {
        $userid=$request;
        return view('admin.dashboard.admin.edit_user',compact('userid'));

    }
    public function requirementcheck($request)
    {
        $getReq=requirement::where('user_id',$request)->orderBy('id','DESC')->get();


        return view('admin.adminform_records.requirements_aspect',compact('getReq'));

    }

    public function ProcessCheck($request)
    {
        $userid=Auth::user()->id;
        $getprocess=Audit::where('user_id',$request)->orderBy('id','DESC')->get();
        $workInstructionsData = Workinstructions::where('user_id', $userid)->get();
        return view('admin.adminform_records.process_audit',compact('getprocess', 'workInstructionsData'));

    }
    /*Delete notifications*/
    public function deleteNotifications(Request $request)
    {
        $id=$request->id;
        DB::table('users_messages')->where('id', $id)->update(['admin_delete' => 1]);
        toastSuccess('Notification message deleted successfully!');
        return back();
    }

    /*Delete Message*/
    public function deleteMessage(Request $request)
    {
        $id=$request->id;
        DB::table('send_notification')->where('id', $id)->update(['admin_delete' => 1]);
        return back();
    }

    public function send_notifications()
    {
        // $getprocess=Audit::where('user_id',$request)->get();
        $users=AddUsers::where('role_type','user')->get();
        $adminmessage=SendNotifications::join('users','users.id','=','send_notification.send_to')
            ->select('send_notification.id as notification_id','send_notification.created_at as notification_created_at','send_notification.*','users.*')
            ->where('admin_delete',false)
            ->orderby('notification_id','desc')
            ->get();
        return view('admin.dashboard.admin.send_notifications',compact('users','adminmessage'));

    }  
    
    public function send_message(Request $request)
    {
        // $getprocess=Audit::where('user_id',$request)->get();
        if($request->ajax())
        {
            //  dd($request->start_date);
            //  dd($request->all());
             //&& $request->month
            if($request->type=="month")
            {
                $start_date = date("Y-m-d", strtotime($request->start_date));
                $end_date =  date('Y-m-d', strtotime("+1 day", strtotime($request->end_date)));
              //  $end_date = date("Y-m-d", strtotime($request->end_date));
                //$end_date = date("YYYY-mm-dd",$request->end_date);
               // dd($end_date);
                if(isset($request->filter_by_certificate)){
                  
                    if($request->filter_by_certificate=="iso9001_certificate"){  
                        $users=AddUsers::where([["last_login",">=", $start_date],["last_login","<=", $end_date]])->where("iso9001_certificate","!=", NULL)->where("iso14001_certificate", NULL)->where("iso45001_certificate",NULL)->get();
                    }else if($request->filter_by_certificate=="iso14001_certificate"){
                        $users=AddUsers::where([["last_login",">=", $start_date],["last_login","<=", $end_date]])->where("iso14001_certificate","!=", NULL)->where("iso9001_certificate", NULL)->where("iso45001_certificate",NULL)->get();
                    }else if($request->filter_by_certificate=="iso45001_certificate"){
                        $users=AddUsers::where([["last_login",">=", $start_date],["last_login","<=", $end_date]])->where("iso45001_certificate","!=", NULL)->where("iso9001_certificate", NULL)->where("iso14001_certificate",NULL)->get();
                    }else if($request->filter_by_certificate=="all"){
                        $users = AddUsers::whereBetween('last_login',[$start_date,$end_date])
                            ->where(function($q)
                        {
                          $q->orWhereNotNull("iso9001_certificate")->orWhereNotNull("iso14001_certificate")->orWhereNotNull("iso45001_certificate");
                        })->get();
//                        $users=AddUsers::where([["last_login",">=", $start_date],["last_login","<=", $end_date]])->whereNotNull("iso9001_certificate")->orWhereNotNull("iso14001_certificate")->orWhereNotNull("iso45001_certificate")->get();
                    }else if($request->filter_by_certificate=="ims"){
                        $users = AddUsers::whereBetween('last_login',[$start_date,$end_date])
                            ->where(function($q)
                            {
                                $q->whereNotNull("iso9001_certificate")->whereNotNull("iso14001_certificate")->whereNotNull("iso45001_certificate");
                            })->get();
//                        $users=AddUsers::where([["last_login",">=", $start_date],["last_login","<=", $end_date]])->where("iso45001_certificate","!=", NULL)->where("iso9001_certificate","!=", NULL)->where("iso14001_certificate","!=", NULL)->get();
                    }
                    // if(isset($request->start_date) && isset($request->end_date)){
                    //     dd("here");
                    // }else if(isset($request->start_date) && $request->end_date==NULL){
                    //     dd("start date only");
                    // }else if(isset($request->end_date) && $request->start_date==NULL){
                    //     dd("end date only");
                    // }else{
                    //     dd("no date selected");
                    // }
                    // dd($users);
                }else{
                    dd("Please select Certification");
                }
                // $users=AddUsers::where("last_login",">", Carbon::now()->subMonths($request->month))->get(); 
            }
            elseif($request->type="certificate" && $request->cert){
                //  dd($request->cert);
                if($request->cert=="iso9001_certificate"){
                    $users=AddUsers::where("iso9001_certificate","!=",'')->whereNull('iso14001_certificate')->whereNull('iso45001_certificate')->get(); 
                }else if($request->cert=="iso14001_certificate"){
                    $users=AddUsers::where("iso14001_certificate","!=",'')->whereNull('iso9001_certificate')->whereNull('iso45001_certificate')->get(); 
                }else if($request->cert=="iso45001_certificate"){
                    $users=AddUsers::where("iso45001_certificate","!=",'')->whereNull('iso9001_certificate')->whereNull('iso14001_certificate')->get(); 
                }
                else if($request->cert=="all"){
                    // $users=AddUsers::where("iso9001_certificate","!=",'')->where("iso14001_certificate","!=","")->where("iso45001_certificate","!=","")->get();
                    $users=AddUsers::whereNotNull("iso9001_certificate")->orWhereNotNull("iso14001_certificate")->orWhereNotNull("iso45001_certificate")->where('id', '!=', 1)->get();

                }else if($request->cert=="ims"){
                    $users=AddUsers::where([["iso9001_certificate","!=", ''], ["iso14001_certificate","!=", ''], ["iso45001_certificate","!=", '']])->get();
                    // $users=AddUsers::where("iso9001_certificate","!=",'')->where("iso14001_certificate","!=","")->where("iso45001_certificate","!=","")->get();
                }
                else{
                    $users=AddUsers::where("".$request->cert."","!=","")->get();
                }
                
            }
            else{
               $users=AddUsers::where('role_type','user')->get();
            }
            if(isset($users))
            {
                $list='';
                $option='';
                $i=1;
                foreach($users as $us)
                {
                    $option.='<option value="'.$us->id.'">'.$us->name.'</option>';
                    $list.='<li class=""><label for=ms-opt-'.$i.'" style="padding-left: 23px;"><input type="checkbox" value='.$us->id.' title='.$us->name.' id="ms-opt-'.$i.'"/>'.$us->name.'</label></li>';
                    $i++;
                }
                echo json_encode(array($list,$option));
            }else{
                echo"error";
            }
        }
        else{
            // if($request->id == 1 || $request->id == 2){
            //     $users=AddUsers::where('role_type','user')->where('user_type', $request->id)->get();
            //     $list= '<div class="kt-input-icon kt-input-icon--right"  style="margin-top: 10px;">';
            //     $list .= '<select name="userid[]" id="langOpt3" class="form-control" multiple>';
            //         $i = 1; 
            //     foreach($users as $user){
            //         $list .= ' <option value="'.$user->id.'">'.$user->name.' </option>';
            //         $i++;
            //     }
        
            //     $list .= '</select>
    		// 		</div>';
            // }else{
            //     $users=AddUsers::where('role_type','user')->get();
            // }
            $users=AddUsers::where('role_type','user')->get();
            $adminmessage=SendNotifications::join('users','users.id','=','send_notification.send_to')
            ->select('send_notification.id as notification_id','send_notification.*','users.*')
            ->where('admin_delete',false)
            ->orderby('notification_id','desc')
            ->get();
            
            return view('admin.dashboard.admin.send_message',compact('users','adminmessage'));
        }
    }

    public function fetchUsers(Request $request){
        if($request->id == 1 || $request->id == 2){
            $users=AddUsers::where('role_type','user')->where('user_type', $request->id)->get();
        }else{
            $users=AddUsers::where('role_type','user')->get();
        }

        if(isset($users) && count($users) > 0){
            $html = view('components.users_dropdown', ['users' => $users])->render();
            return response()->json(['success' => true, "html" => $html], 200);
        }else{
            return response()->json(['success' => false, "msg" => "Some error occurred"], 400);
        }
    }

// function used to show sent notification on Admin Panel
    public function sentNotification(){
        $user_id = Auth::user()->id;
        $users = SendNotifications::join('users','users.id','=','send_notification.send_to')
        ->select('send_notification.*', 'users.id', 'users.name', 'users.company_name')
        ->where('send_notification.send_by', $user_id)
        ->whereRaw('send_notification.updated_at = (SELECT MAX(updated_at) FROM send_notification WHERE send_notification.send_to = users.id)')
        ->orderby('send_notification.updated_at', 'desc')
        ->groupBy('users.id')
        ->get();
        return view('admin.dashboard.admin.sentNotification',compact('users'));
    }

    public function addreq(Request $request){
        //   dd($request->all());
        $addreq= new requirement;
        $addreq->user_id=$request->user_id;
        $addreq->requirment_title=$request->requirement;
        $addreq->completion_date=$request->req_date;
        $addreq->periods=$request->period;
        $createdAt = Carbon::parse($request->req_date)->addMonth($request->period);
         $addreq->due_date=$createdAt;


         $addreq->save();
        return redirect()->back();
    }

    public function sendNotifications(Request $request){

        // $request->validate([
        //         'userid' => 'required'
        //     ],
        // );

        $users = $request->userid;
        // dd($users);
        $data = [];

        if ($request->file('attachment')) {
            $imagePath = $request->file('attachment');
            $imageName = uniqid() . "." . $request->file('attachment')->extension();
            $path = $request->file('attachment')->storeAs('uploads/user', $imageName, 'public');
            $request->file('attachment')->move(public_path('uploads/user'), $imageName);
            $data['attachement'] = $path;
        }
        $user_id = Auth::user()->id;

        $data['title']=$request->input('title');
        $data['message']=$request->input('message');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['send_by'] = $user_id;
        if($users==NULL){
            return redirect()->back()->with('error', 'Please select at least one User');
        }else{
            foreach ($users as $user){
                $randomBytes = random_bytes(4); 
                $randomInt = unpack('L', $randomBytes)[1];
                $data['unique_id'] = intval(microtime(true) + $randomInt);
                $data['send_to'] = $user;
                // storing data into database
                SendNotifications::create($data);
                // sending notification to user(s)
                $user = User::where('id', $user)->first();
                $senderName = Auth::user()->name;
                // $notificationMessage = new messageNotification($senderName);
                // $user->notify($notificationMessage);
                DB::table('users_messages')->where('id', $request->input('replied'))->update(['replied' => 1]);
            }
            return redirect()->back()->with('success', 'Your message has been Sent');
        }
    }

    public function AuditsCheck($request){
        $auditreport=Qmsaudit::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.qms_audit',compact('auditreport'));
    }

    public function nonConformCheck(Request $request, $id)
    {
        // $noneConform=Nonconform::where('user_id',$request)->get();
        // return view('admin.adminform_records.non_conformities',compact('noneConform'));
        // dd($id);

        $noneConform=Nonconform::where('user_id',$id)->get();
        $customers = DB::table('tbl_suppliers')->where('user_id',$id)->get();
        $employees= DB::table('tbl_employees')->where('user_id',$id)->get();

        // dd($customers);

        // $customers_nonconform = DB::table('tbl_noconformance')->join('tbl_customer','tbl_noconformance.customerID','tbl_customer.idNumber')
        // ->select('tbl_noconformance.id as noid','tbl_noconformance.*','tbl_customer.*')
        // ->where('tbl_noconformance.user_id',$id)->where('tbl_customer.user_id',$id)->orderBy('tbl_noconformance.id','DESC')->get();

        $customers_nonconform = DB::table('tbl_noconformance')->join('tbl_suppliers','tbl_noconformance.customerID','tbl_suppliers.idnumber')
        ->select('tbl_noconformance.id as noid','tbl_noconformance.*','tbl_suppliers.*')
        ->where('tbl_noconformance.user_id',$id)->where('tbl_suppliers.user_id',$id)->orderBy('tbl_noconformance.id','DESC')->get();
        
        // dd($customers_nonconform);
        return view('admin.adminform_records.non_conformities',compact('noneConform','customers','customers_nonconform','employees'));

    }

    public function customerCheck($request){
        $customer=customers::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.customer',compact('customer'));

    }

    public function customerReviewad($request){
        $all_customers=customers::where('user_id',$request)->get();
        $customer_review=customer_review::where('user_id',$request)->orderBy('id','DESC')->get();

        return view('admin.adminform_records.customer_review',compact('customer_review','request','all_customers'));

    }
    public function supplierCheck($request){
        $supplier=Supplier::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.supplier',compact('supplier'));

    }
    public function calibrationcheck($request){
        $caliber=calibration::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.calibration_record',compact('caliber'));

    }
    
    public function EmployeCheck($request)
    {
        $userinfo=Employee::with('user')->where('user_id',$request)->orderBy('id','DESC')->get();
       // $employess=Employee::join('tbl_employees_skills','tbl_employees_skills.empid','=','tbl_employees.id')->where('tbl_employees.user_id',$request)->get();
        // dd($employess);
        $employess = DB::table('tbl_employees_skills')
            ->select('tbl_employees_skills.skill_id as skill_id',
            'tbl_employees_skills.empskill as empskill',
            'tbl_employees.empNumber as empNumber',
            'tbl_employees.surname as surname',
            'tbl_employees.first_name as first_name',
            'users.email'
            )
            ->join('tbl_employees','tbl_employees_skills.empid','=','tbl_employees.id')
            ->join('users','users.id','=','tbl_employees.user_id')
            ->where('tbl_employees_skills.user_id','=',$request)
            ->orderBy('tbl_employees_skills.created_at','DESC')
            ->get();
      //  $emptraining=Employee::join('tbl_employees_traning','tbl_employees_traning.empid','=','tbl_employees.id')->  where('tbl_employees.user_id',$request)->get();

      $emptraining=Employee::join('tbl_employees_traning','tbl_employees_traning.empid','=','tbl_employees.id')
          ->where('tbl_employees.user_id',$request)
          ->orderBy('tbl_employees_traning.created_at','DESC')
          ->get();
        

          $users = Employee::where('user_id',$request)->orderBy('id','DESC')->get();
          $wp_users = [];
            foreach($users as $user){
            $wp_users[] = Certificate::where('user_email', $user->email)->get();
            }
      return view('admin.adminform_records.employess',compact('userinfo','employess','emptraining', 'wp_users'));

    }
    
    public function managementCheck($request)
    {
        $mgtrev=Mgtreview::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.managment_reviews',compact('mgtrev'));
    }
    public function maintainRecCheck($request){

        $mainrecord=Maintain_rec::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.maintance_record',compact('mainrecord'));

    }
    public function AccidentCheck($request){
        $riskassesment=AccidentRisk::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.accident_risk_assesment',compact('riskassesment'));

    }
    public function riskAssesmntCheck($request){
       $assessment=Assessment::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.risk_assessment',compact('assessment'));
    }
    public function workinstructionCheck($request){
        $work=Workinstructions::where('user_id',$request)->orderBy('id','DESC')->get();
        $employess = Employee::where('user_id',$request)->get();
        // $employess=Workinstructions::join('tbl_employees','tbl_employees.systemid','=','tbl_workinstruction.empId')->where('tbl_employees.user_id',$request)->get();
        return view('admin.adminform_records.work_instruction',compact('work','employess'));
    }


    public function additionalpolicies($userid)
    {
        // Retrieve policies specific to the user
        $qualityPolicy = CustomManual::where('user_id', $userid)->where('status', 1)->first();
        $environmentalPolicy = CustomManual::where('user_id', $userid)->where('status', 2)->first();
        $healthSafetyPolicy = CustomManual::where('user_id', $userid)->where('status', 3)->first();
    
        return view('admin.adminform_records.additionalpolicies', compact('qualityPolicy', 'environmentalPolicy', 'healthSafetyPolicy'));
    }
    




    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AddUsers  $addUsers
     * @return \Illuminate\Http\Response
     */
    public function edit(AddUsers $addUsers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AddUsers  $addUsers
     * @return \Illuminate\Http\Response
     */
      public function update(Request $request)
    {
        $id = $request->id;
        $user = AddUsers::find($id);
        if ($request->input('password') != '' || !empty($request->input('password'))) 
        {
            $pass = $request->input('password');
            $user->password = Hash::make($pass);
        }
        if ($request->file('user_image')) {
            $imagePath = $request->file('user_image');
            $imageName = uniqid() . "." . $request->file('user_image')->extension();

            $path = $request->file('user_image')->storeAs('/uploads/user', $imageName, 'public');
            $request->file('user_image')->move(public_path('/uploads/user'), $imageName);
            $user->profile_image = $path;
        }
        $user->persone_iso = $request->input('person_iso');
        $user->contact_number_iso = $request->input('contact_iso');
        $user->emailaddress_iso = $request->input('email_iso');

        if ($request->file('iso9001_certificate')) {
            $file_path = $request->file('iso9001_certificate');
            $file_name = uniqid() . "." . $request->file('iso9001_certificate')->extension();

            $path = $request->file('iso9001_certificate')->storeAs('/uploads/user', $file_name, 'public');
            $request->file('iso9001_certificate')->move(public_path('/uploads/user'), $file_name);
            $user->iso9001_certificate = $path;
        }
        $user->iso9001_expirydate = $request->input('iso9001_expirydate');
        $user->iso9001_description = $request->input('iso9001_description');

        if ($request->file('iso14001_certificate')) {
            $iso14001_certificate_path = $request->file('iso14001_certificate');
            $iso14001_certificate_name = uniqid() . "." . $request->file('iso14001_certificate')->extension();

            $iso14001_certificate_path = $request->file('iso14001_certificate')->storeAs('/uploads/user', $iso14001_certificate_name, 'public');
            $request->file('iso14001_certificate')->move(public_path('/uploads/user'), $iso14001_certificate_name);
            $user->iso14001_certificate = $iso14001_certificate_path;

        }
        $user->iso14001_expirydate = $request->input('iso14001_expirydate');
        $user->iso14001_description = $request->input('iso14001_description');

        if ($request->file('iso45001_certificate')) {
            $iso45001_certificate_path = $request->file('iso45001_certificate');
            $iso45001_certificate_name = uniqid() . "." . $request->file('iso45001_certificate')->extension();

            $iso45001_certificate_path = $request->file('iso45001_certificate')->storeAs('/uploads/user', $iso45001_certificate_name, 'public');
            $request->file('iso45001_certificate')->move(public_path('/uploads/user'), $iso45001_certificate_name);
            $user->iso45001_certificate = $iso45001_certificate_path;

        }

        $user->iso45001_expirydate = $request->input('iso45001_expirydate');
        $user->iso45001_description = $request->input('iso45001_description');

        $user->country=$request->input('country');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        if ($request->input('phoneflag') == "preferred") 
        {
            $phoneflag = "us";
        } else {
            $phoneflag = $request->input('phoneflag');
        }

        $user->phoneflag = $phoneflag;
        $user->phonecode = $request->input('phonecode');
        $user->iso_phone_code = $request->input('isophonecode');
        if ($request->input('isophoneflag') == "preferred") {
            $isophoneflag = "us";
        } else {
            $isophoneflag = $request->input('isophoneflag');
        }
        $user->iso_phone_flag = $isophoneflag;

        $user->director = $request->input('director');
        $user->sales_process = $request->input('sales_process');

        if ($request->file('company_profile')) {
            $imagePath = $request->file('company_profile');
            $imageName = uniqid() . "." . $request->file('company_profile')->extension();

            $path = $request->file('company_profile')->storeAs('/uploads/user', $imageName, 'public');
            $request->file('company_profile')->move(public_path('/uploads/user'), $imageName);

            $user->company_profile = $path;
        }

        if (Schema::hasColumns('users', ['audit_report'])) {
            if ($request->file('audit_report')) {
                if (File::exists(public_path($user->audit_report))) {
                    File::delete(public_path($user->audit_report));
                }
                $audit_report_path = $request->file('audit_report');
                $audit_report_name = uniqid() . "." . $request->file('audit_report')->extension();

                $audit_report_path = $request->file('audit_report')->storeAs('/uploads/user/audit_report', $audit_report_name, 'public');
                $request->file('audit_report')->move(public_path('/uploads/user/audit_report'), $audit_report_name);
                $user->audit_report = $audit_report_path;
            }
        }
        $user->audit_comment=$request->input('audit_comment');
        $user->expiry_date = $request->input('expiry_date');

        $user->company_name = $request->input('company_name');
        $user->company_address = $request->input('company_address');
        $user->purchasing_process = $request->input('purchasing_process');
        $user->servicing_process = $request->input('servicing_process');
        $user->competency_process = $request->input('competency_process');
        $user->scope = $request->input('business_scopes');
        $user->order_number = $request->input('order_number');
        $user->Company_overview = $request->input('Company_overview');

        $user->save();

        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AddUsers  $addUsers
     * @return \Illuminate\Http\Response
     */

         public function interested_parties($request){
        //  dd($request);


 $id = intval($request);

        $interested=DB::table('interested_parties')->where('user_id',$id)->orderBy('id','DESC')->get();
        // dd($interested);
		//$auditreport=Qmsaudit::where('user_id',$request)->get();
        //return view('admin.adminform_records.interested_parties',compact('auditreport'));
        return view('admin.adminform_records.interested_parties',compact('interested','request'));
	}
    public function destroy(Request $req)
    {
        $userid=$req->id;
        $user=AddUsers::find($userid);
        if (File::exists(public_path($user->audit_report))) {
            File::delete(public_path($user->audit_report));
        }
        $loginHistories = LoginHistoryUser::where('user_id', $userid)->delete();
        $user->delete();

        return redirect('/view_user')->with("success", "User deleted Successfully.");

    }

    public function updateadmin(Request $request)
    {

        $id=$request->requirment_id;
        $requirement= requirement::find($id);
        $duedate=$request->input('periods');
        $completionDate=$request->input('completion_date');
        $effectiveDate = date('Y-m-d', strtotime($completionDate . "'.$duedate.' months") );
        $next_due_date = date('d-m-Y', strtotime($completionDate. ' +30 days'));
        $requirement->requirment_title=$request->input('requirment_title');
        $requirement->completion_date=$request->input('completion_date');
        $requirement->periods=$request->input('periods');
        $requirement->due_date=$next_due_date;
        $requirement->save();
        //sign them in
        $notification = [
            'message' => 'Record  updated successfully.!',
            'alert-type' => 'success'
        ];
        return redirect()->back();
        // return redirect("/requiremntCheck/$returnId")->with($notification);
    }
       //store audit info
    public function storeadminaudit(Request $request)
    {
		$this->validate($request,[
            'processAudit'=>'required',
            'auditor'=>'required',
            'auditDate'=>'required',
            'nonConformities'=>'required',
            'Observations'=>'required',
            'nonConfReport'=>'required',
            'AdutiActions'=>'required',
            'dateFrequency'=>'required',
        ]);

        try{
             $Audit= new Audit;
             $Audit->user_id=$request->user_id;
             $Audit->auditId=111;
             $Audit->processAudit=$request->input('processAudit');
             $Audit->auditor=$request->input('auditor');
             $Audit->auditDate=$request->input('auditDate');
             $Audit->nonConformities=$request->input('nonConformities');
             $Audit->Observations=$request->input('Observations');
             $Audit->nonConfReport=$request->input('nonConfReport');
             $Audit->AdutiActions=$request->input('AdutiActions');
             $Audit->dateFrequency=$request->input('dateFrequency');
             $Audit->qmsCorects=$request->input('qmsCorects');
             $Audit->evidence=$request->input('evidence');
             $Audit->needExpactations=$request->input('needExpactations');
             $Audit->evidance2=$request->input('evidance2');
             $Audit->correction3=$request->input('correction3');
             $Audit->evidence3=$request->input('evidence3');
             $Audit->correction4=$request->input('correction4');
             $Audit->evidance4=$request->input('evidance4');
             $Audit->correction5=$request->input('correction5');
             $Audit->evidence5=$request->input('evidence5');
             $Audit->correction6=$request->input('correction6');
             $Audit->evidance7=$request->input('evidance7');
             $Audit->correction7=$request->input('correction7');
             $Audit->evidance8=$request->input('evidance8');
             $Audit->correction9=$request->input('correction9');
             $Audit->evidance9=$request->input('evidance9');
             $Audit->correction10=$request->input('correction10');
             $Audit->evidance10=$request->input('evidance10');
             $Audit->any_issues=$request->input('any_issues');

            //Check if user attach evidence
            if ($request->file('attach_evidence') && Schema::hasColumn('tbl_audit','attach_evidence')) {
                $file = $request->file('attach_evidence');
                $path = '/uploads/user/attach_evidence/';
                $Audit->attach_evidence = HelperFunctions::saveFile($path,$file);
            }

             $Audit->save();

              return redirect()->back();
        }catch(Exception $exc){
            print_r($exc->getMessage());
        }
    }
          /// to add admin note for user      
public function addUsernote(Request $request)
{
    $request->validate([
        'note' => 'required|string',
        'note_file' => 'nullable|mimes:jpeg,png,jpg,gif,doc,docx,xls,xlsx,pdf|max:20480' // 20MB
    ]);
  
    try {
        $note = new UserNotesHistory;
        $note->company_id = $request->editcompanyid;
        $note->note = $request->input('note');
        $note->dated = now();

       if ($request->hasFile('note_file')) {
    $file = $request->file('note_file');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('uploads/notes'), $filename);
    $note->note_img = $filename; // this field now stores all file types
    }

        $note->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function updateUsernote(Request $request, $id)
{
    $request->validate([
        'note' => 'required|string',
        'note_file' => 'nullable|mimes:jpeg,png,jpg,gif,doc,docx,xls,xlsx,pdf|max:20480' // 20MB
    ]);
    try {
        $note = UserNotesHistory::findOrFail($id);
        $note->note = $request->input('note');
        $note->dated = now();

        // Handle new file upload
        if ($request->hasFile('note_file')) {
            // Optional: delete old file if it exists
            if ($note->note_img && file_exists(public_path($note->note_img))) {
                unlink(public_path($note->note_img));
            }

            $file = $request->file('note_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/notes'), $filename);
            $note->note_img =  $filename;
        }

        $note->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function deleteUsernote($id)
{
    try {
        $note = UserNotesHistory::findOrFail($id);
        $note->delete();

        return response()->json(['success' => true]);
    } catch (\Exception $exc) {
        return response()->json(['error' => $exc->getMessage()], 500);
    }
}
   
    // function used to display the messages in old Inbox
    public function receive_notifications()
    {
        $notif = DB::table('users_messages')->orderBy('id', 'desc')->get();
		return view('admin.dashboard.admin.receive_notifications',compact('notif'));
    }  

    // public function unreadMessages(){
    //     $user_id = Auth::user()->id;
    //     $unreadMessages = SendNotifications::join('users', 'users.id','=','send_notification.send_by')
    //     ->select('send_notification.*', 'users.name', 'users.company_name')
    //     ->where('send_notification.send_to',$user_id)
    //     ->where('send_notification.status', 0)
    //     ->groupBy('send_notification.unique_id')
    //     ->orderBy('send_notification.updated_at', 'desc')
    //     ->get();
    //     // dd($unreadMessages);
    //     return view('admin.dashboard.includes.sidebar', compact('unreadMessages'));
    // }



    // function used to show messages in New Inbox
    public function receivedNotifications(Request $request){        
        $userid=Auth::user()->id;
        $message_info = SendNotifications::join('users', 'users.id','=','send_notification.send_by')
        ->select('send_notification.*', 'users.id', 'users.name', 'users.company_name')
        ->where('send_notification.send_to', $userid)
        ->whereRaw('send_notification.updated_at = (SELECT MAX(updated_at) FROM send_notification WHERE send_notification.send_by = users.id)')
        ->orderBy('send_notification.updated_at', 'desc')
        ->groupBy('users.id')
        ->get();
        // dd($message_info);
        return view('admin.dashboard.admin.receive_notification_inbox', compact('message_info'));      
    }

    // this function is used for previous message inbox -  now its just a backup 
    // public function receivedNotifications(Request $request){
    //     $userid=Auth::user()->id;
    //     $message_info=SendNotifications::join('users', 'users.id','=','send_notification.send_by')
    //     ->select('send_notification.*', 'users.name', 'users.company_name')
    //     ->where('send_notification.send_to',$userid)
    //     ->groupBy('send_notification.unique_id')
    //     ->orderBy('send_notification.updated_at', 'desc')
    //     ->get();
    //     return view('admin.dashboard.admin.receive_notification_inbox', compact('message_info'));      
    // }

    // public function markAsRead(Request $request) {
    //     $item_id = $request->input('item_id');
    //     $userId = Auth::user()->id;
    //     if($userId == 1011){
    //         sendNotifications::where('send_to', 1011)->where('unique_id', $item_id)->update(['status'=>1]);
    //     }else{
    //         SendNotifications::where('unique_id', $item_id)->update(['status' => 1]);
    //     }
    // }
    

    public function individualMessageAdmin(Request $request){
        // dd($request->all());
        $userId = Auth::user()->id;
        $otherUserId = $_GET['id'];
        // $messageId = $_GET['messageId'];
        $message_information = SendNotifications::join('users', 'users.id','=','send_notification.send_by')
        ->where('send_to', $userId)
        ->where('send_by', $otherUserId)
        ->orwhere('send_by', $userId)
        ->where('send_to', $otherUserId)
        ->orderby('send_notification.id', 'desc')
        ->select('send_notification.*', 'users.name')
        ->get();

        // Getting updated at field
        $updatedAt = SendNotifications::where('send_to', $userId)
        ->where('send_by', $otherUserId)
        ->orwhere('send_by', $userId)
        ->where('send_to', $userId)
        ->orderby('send_notification.id', 'desc')
        ->value("updated_at");
        
        // for marking as unread
        $unreadAdminMessage = SendNotifications::where('send_to', $userId)
        ->where('send_by', $otherUserId)
        ->orwhere('send_by', $userId)
        ->where('send_to', $userId)
        ->orderby('send_notification.id', 'desc')
        ->update(['status' => 1, 'updated_at' => $updatedAt]);

        
        // $user_id = Auth::user()->id;
        // $parent_message_id = SendNotifications::where('unique_id', $messageId)
        // ->where('send_by', $user_id)
        // ->first(['id']);
        $other_user_detail = User::where('id', '=', $otherUserId)->first();
        return view('admin.dashboard.admin.admin_individual_message', compact('message_information', 'other_user_detail'));
    }

    // backup for individual Message Admin 

    // public function individualMessageAdmin(){
    //     $messageId = $_GET['id'];
    //     // dd()
    //     $userId = Auth::user()->id;
    //     $message_information = SendNotifications::join('users', 'users.id','=','send_notification.send_by')
    //     ->where('unique_id', $messageId)
    //     ->orderby('send_notification.id', 'desc')
    //     ->select('send_notification.*', 'users.name')
    //     ->get();

    //     $user_id = Auth::user()->id;
    //     $parent_message_id = SendNotifications::where('unique_id', $messageId)
    //     ->where('send_by', $user_id)
    //     ->first(['id']);

    
    //     // dd($parent_message_id);
    //     return view('admin.dashboard.admin.admin_individual_message', compact('message_information', 'parent_message_id'));
    // }

    // function used to store the message from the Admin 
    public function storeReplyMessageAdmin(Request $request){
        // dd($request->all());
        $user_id = Auth::user()->id;
        $receiver = $request->input('receiver');
        $sender = $request->input('sender');
        $parentId = $request->input('parentId');
        $parentIdArray = json_decode($parentId, true);
        if (is_array($parentIdArray) && isset($parentIdArray['id'])) {
            $id = $parentIdArray['id'];
        }
        $reply = new SendNotifications;
        $reply->title = $request->input('title');
        $reply->message = $request->input('replyMessage');
        
        if($receiver == $user_id){
            $reply->send_by = $user_id;
            $reply->send_to = $sender;
        }else{
            $reply->send_to = $receiver;
            $reply->send_by = $user_id;
        }

        if ($request->hasFile('attachment')) {
            $imagePath = $request->file('attachment');
            $imageName = uniqid() . "." .$imagePath->extension();
            $path = $request->file('attachment')->storeAs('uploads/user', $imageName, 'public');
            $request->file('attachment')->move(public_path('uploads/user'), $imageName);
            $reply->attachement = $path;
        }

        $reply->created_at = date('Y-m-d H:i:s');
        $reply->unique_id = $request->input('messageId');
        $reply->save();
        if(!empty($id)){
            SendNotifications::where('id', $id)->update(['status'=>0]);
        }
        return redirect()->back();
    }

    // updateauditadmin
    public function updateauditadmin(Request $request)
    {

        $id=$request->id;

        try{
            $Audit=Audit::find($id);
             $Audit->processAudit=$request->input('processAudit');
             $Audit->auditor=$request->input('auditor');
             $Audit->auditDate=$request->input('auditDate');
             $Audit->nonConformities=$request->input('nonConformities');
             $Audit->Observations=$request->input('Observations');
             $Audit->nonConfReport=$request->input('nonConfReport');
             $Audit->AdutiActions=$request->input('AdutiActions');
             $Audit->dateFrequency=$request->input('dateFrequency');

			 $Audit->qmsCorects=$request->input('qmsCorects');
             $Audit->evidence=$request->input('evidence');
             $Audit->needExpactations=$request->input('needExpactations');
             $Audit->evidance2=$request->input('evidance2');
             $Audit->correction3=$request->input('correction3');
             $Audit->evidence3=$request->input('evidence3');
             $Audit->correction4=$request->input('correction4');
             $Audit->evidance4=$request->input('evidance4');
             $Audit->correction5=$request->input('correction5');
             $Audit->evidence5=$request->input('evidence5');
             $Audit->correction6=$request->input('correction6');
             $Audit->evidance7=$request->input('evidance7');
             $Audit->correction7=$request->input('correction7');
             $Audit->evidance8=$request->input('evidance8');
             $Audit->correction9=$request->input('correction9');
             $Audit->evidance9=$request->input('evidance9');
             $Audit->correction10=$request->input('correction10');
             $Audit->evidance10=$request->input('evidance10');
             $Audit->any_issues=$request->input('any_issues');
            //Check if user attach evidence
            if ($request->file('attach_evidence') && Schema::hasColumn('tbl_audit','attach_evidence')) {
                //Delete previous attach evidence if exist
                if (File::exists(public_path($Audit->attach_evidence))) {
                    File::delete(public_path($Audit->attach_evidence));
                }

                $file = $request->file('attach_evidence');
                $path = '/uploads/user/attach_evidence/';
                $Audit->attach_evidence = HelperFunctions::saveFile($path,$file);
            }

             $Audit->save();

             return redirect()->back();
        }catch(Exception $exc){
            print_r($exc->getMessage());

            return redirect()->back();
        }

        // return redirect("/requiremntCheck/$returnId")->with($notification);
    }


    public function updateAuditCheck(Request $request){
        $id = $request->id;

        $Qmsaudit=Qmsaudit::find($id);

             $Qmsaudit->qmsCorects=$request->input('qmsCorects');
             $Qmsaudit->evidence=$request->input('evidence');
             $Qmsaudit->needExpactations=$request->input('needExpactations');
             $Qmsaudit->evidance2=$request->input('evidance2');
             $Qmsaudit->correction3=$request->input('correction3');
             $Qmsaudit->evidence3=$request->input('evidence3');
             $Qmsaudit->correction4=$request->input('correction4');
             $Qmsaudit->evidance4=$request->input('evidance4');
             $Qmsaudit->correction5=$request->input('correction5');
             $Qmsaudit->evidence5=$request->input('evidence5');
             $Qmsaudit->correction6=$request->input('correction6');
             $Qmsaudit->evidance7=$request->input('evidance7');
             $Qmsaudit->correction7=$request->input('correction7');
             $Qmsaudit->correction8=$request->input('correction8');
             $Qmsaudit->evidance8=$request->input('evidance8');
             $Qmsaudit->correction9=$request->input('correction9');
             $Qmsaudit->evidance10=$request->input('evidance10');
             $Qmsaudit->correction11=$request->input('correction11');
             $Qmsaudit->evidance12=$request->input('evidance12');
             $Qmsaudit->correction12=$request->input('correction12');
             $Qmsaudit->evidence13=$request->input('evidence13');
             $Qmsaudit->correction13=$request->input('correction13');
             $Qmsaudit->evidance14=$request->input('evidance14');
             $Qmsaudit->correction14=$request->input('correction14');
             $Qmsaudit->evidence17=$request->input('evidence17');
             $Qmsaudit->correction15=$request->input('correction15');
             $Qmsaudit->evidence15=$request->input('evidence15');
             $Qmsaudit->correction16=$request->input('correction16');
             $Qmsaudit->correciton17=$request->input('correciton17');
             $Qmsaudit->evidence18=$request->input('evidence18');
             $Qmsaudit->correction18=$request->input('correction18');
             $Qmsaudit->evidence19=$request->input('evidence19');
             $Qmsaudit->correction19=$request->input('correction19');
             $Qmsaudit->evidence20=$request->input('evidence20');
             $Qmsaudit->correction20=$request->input('correction20');
             $Qmsaudit->evidence21=$request->input('evidence21');
             $Qmsaudit->correction21=$request->input('correction21');
             $Qmsaudit->evidence22=$request->input('evidence22');
             $Qmsaudit->correction22=$request->input('correction22');
             $Qmsaudit->evidence23=$request->input('evidence23');
             $Qmsaudit->correction23=$request->input('correction23');
             $Qmsaudit->evidence24=$request->input('evidence24');
             $Qmsaudit->correction24=$request->input('correction24');
             $Qmsaudit->evidence25=$request->input('evidence25');
             $Qmsaudit->correction25=$request->input('correction25');
             $Qmsaudit->evidence26=$request->input('evidence26');
             $Qmsaudit->correction26=$request->input('correction26');
             $Qmsaudit->evidence27=$request->input('evidence27');
             $Qmsaudit->correction27=$request->input('correction27');
             $Qmsaudit->evidence28=$request->input('evidence28');
             $Qmsaudit->evidence31=$request->input('evidence31');
             $Qmsaudit->competedDate=$request->input('competedDate');
             $Qmsaudit->auditrName=$request->input('auditrName');

             $Qmsaudit->save();
             return redirect()->back();
    }

    public function destroyreq(Request $req)
    {
        // $userid=$req->req_id;
        $userid=$req->id;
        // echo $userid;exit;
        $req=requirement::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
     /*work for chemical control*/
    public function chemicalcheck($request)
    {
        $chemical=Chemical::where('user_id',$request)->orderBy('id','DESC')->get();
        return view('admin.adminform_records.chemical_record',compact('chemical'));
    }
     public function deleteChemical2(Request $request)
    {
        // dd($request->id);
        $userid=$request->id;
        $req=Chemical::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();
    }

    /*end*/
    public function destroyaudit(Request $req)
    {
        $id=$req->id;
        $audit = Audit::find($id);
        if (File::exists(public_path($audit->attach_evidence))) {
            File::delete(public_path($audit->attach_evidence));
        }
        $audit->delete();

        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function auditdeleter(Request $req)
    {
        $userid=$req->id;
        $req=Qmsaudit::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deleteNonConfrm(Request $req)
    {
        $userid=$req->id;
        $req=Nonconform::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deletecustomeradmin(Request $req)
    {
        $userid=$req->id;
        $req=customers::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deleteCustomerRivewAdmin(Request $req)
    {
        $userid=$req->id;
        $req=customer_review::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deleteSupplierAdmin(Request $req)
    {
        $userid=$req->id;
        $req=Supplier::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deletecaliberinfo(Request $req )
    {
        $userid=$req->id;
        $req=calibration::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
     public function deleteEmployeeskill(Request $req)
    {
        $userid=$req->id;
        $req=DB::table('tbl_employees_skills')->where('skill_id', $userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deleteEmployeeadmin(Request $request)
    {
        $type=$request->type;
        if($type=="employee")
        {
            $employee = Employee::find($request->id);
            if (File::exists(public_path($employee->cv))) {
                File::delete(public_path($employee->cv));
            }
            EmpSkills::where('empid',$request->id)->delete();
            EmployeeTraning::where('empid',$request->id)->delete();
            $employee->delete();
        }
        if($type=="employeeskill")
        {
            EmpSkills::where('skill_id',$request->id)->delete();
        }
        if($type=="employeetraining")
        {
            EmployeeTraning::where('traning_id',$request->id)->delete();
        }

        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deletemgtreviewadmin(Request $req)
    {
        $userid=$req->id;
        $req=Mgtreview::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deletemaintanceRecAdmin(Request $req)
    {
        $userid=$req->id;
        $req=Maintain_rec::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deleteRiskadmin(Request $req)
    {
        $userid=$req->id;
        $req=AccidentRisk::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deleteAssesmnetadmin(Request $req)
    {
        $userid=$req->id;
        $req=Assessment::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }
    public function deleteWorkadmin(Request $req)
    {
        $userid=$req->id;
        $req=Workinstructions::find($userid)->delete();
        $notification = [
            'message' => 'Record  Deleted successfully.!',
            'alert-type' => 'success'
        ];

        return redirect()->back();


    }

    public function quality_manual(Request $request)
    {
        $userid= Auth::user()->id;
        $user=User::where('id',$userid)->first();

         return view('dashboard.mannual_policy.quality_manual',compact('user'));

    }

    public function UpdateUserInfo(Request $request)
    {
        // return "sfdfds";
        $id=$request->id;
        $Company_overview=$request->input('Company_overview');
        $user=AddUsers::find($id);
        if($request->input('password') !='' || !empty($request->input('password'))){
            $pass=$request->input('password');
            $user->password=Hash::make($pass);
        }
        $user->phone=$request->input('phone');
        $user->director=$request->input('director');
        $user->sales_process=$request->input('sales_process');
      $user->company_profile=$request->input('company_profile');
      $user->company_address=$request->input('company_address');
      $user->purchasing_process=$request->input('purchasing_process');
      $user->servicing_process=$request->input('servicing_process');
      $user->competency_process=$request->input('competency_process');
      $user->scope=$request->input('scope');
      $user->order_number=$request->input('order_number');
        $user->Company_overview=$request->input('Company_overview');
      $user->save();

    }

    public function all_faqs(){
		$all_faqs  = DB::table('faqs')->get();
		$all_cate  = DB::table('faqs_categories')->get();
        return view('admin.dashboard.admin.view_faqs', compact('all_faqs'), compact('all_cate'));
    }

    public function add_faq(Request $request){
            $insert = DB::table('faqs')->insert(
				array(
					'question' => $request['question'],
					'answer' => $request['answer'],
					'category' => $request['category']
				)
			);
	session()->flash('msg', 'Question added successfully.');
    return redirect()->back();
    }

    public function add_faq_cate(Request $request){
        $insert = DB::table('faqs_categories')->insert(
			array('name' => $request['faq_cate'])
		);
	session()->flash('msg', 'Category added successfully.');
    return redirect()->back();
    }
	public function faq_edit($id){
		$faq  = DB::table('faqs')->where('id', $id)->first();
		//$faq  = DB::select( DB::raw("SELECT * FROM faqs WHERE id = '$id'") )->get();
		//dd($faq);
		$all_cate  = DB::table('faqs_categories')->get();
		//dd($all_cate);
        return view('admin.dashboard.admin.faq_edit', compact('faq'), compact('all_cate'));
	}

	public function faq_update(Request $request){
		//dd($request);
		DB::table('faqs')->where('id', $request['id'])->update(
				array(
					'question' => $request['question'],
					'answer' => $request['answer'],
					'category' => $request['category']
				)
			);
	session()->flash('msg', 'Question updated successfully.');
    return redirect(url('/all_faqs'));
	}

	public function faq_delete(Request $request){
		DB::table('faqs')->delete($request['id']);
			session()->flash('msg', 'FAQ deleted successfully.');
    return redirect()->back();
	}

	public function cat_delete(Request $request){
		DB::table('faqs_categories')->delete($request['id']);
			session()->flash('msg', 'Category deleted successfully.');
        return redirect()->back();
	}

    public function add_quick_link(Request $request){
            $insert = DB::table('quick_links')->insert(
				array(
					'title' => $request['title'],
					'link' =>  $request['link'],
					'category' => $request['category']
				)
			);
	session()->flash('msg', 'Quick link added successfully.');
    return redirect()->back();
    }

    public function all_quick_links(){
        $quick_links  = DB::table('quick_links')->get();
		$all_cate  = DB::table('quick_links_categories')->get();
        return view('admin.dashboard.admin.view_quick_links', compact('quick_links'), compact('all_cate'));
    }

    public function add_quicklink_cate(Request $request){
    $insert = DB::table('quick_links_categories')->insert(
			array('name' => $request['quicklink_cate'])
		);
	session()->flash('msg', 'Quick link category added successfully.');
	 return redirect(url('/all_quick_links'));

    }

	public function quick_link_edit($id){
		$quick_link  = DB::table('quick_links')->where('id', $id)->first();
		$all_cate  = DB::table('quick_links_categories')->get();
        return view('admin.dashboard.admin.quick_link_edit', compact('quick_link'), compact('all_cate'));
	}

	public function quick_link_update(Request $request){

		DB::table('quick_links')->where('id', $request['id'])->update(
				array(
					'title' => $request['title'],
					'link' => 	$request['link'],
					'category' => $request['category']
				)
			);
	session()->flash('msg', 'Quick link updated successfully.');
    return redirect(url('/all_quick_links'));
	}

    public function quick_link_delete(Request $request){
		DB::table('quick_links')->delete($request['id']);
			session()->flash('msg', 'Quick link deleted successfully.');
    return redirect()->back();
	}

	 public function quick_link_category_delete(Request $request){
		DB::table('quick_links_categories')->delete($request->category_id);
		session()->flash('msg', 'Category deleted successfully.');
        return redirect()->back();
	}
/**/

    public function add_video(Request $request){
			$path='';
        if ($request->file('video')) {
			   $request->validate([
            'video' => 'required|mimes:mp4,avi|max:40096'
            ]);
            $vidPath = $request->file('video');
            $vidName = uniqid().".".$request->file('video')->extension();
            $path = $request->file('video')->storeAs('uploads/explainer_videos', $vidName, 'public');
          $request->file('video')->move(public_path('uploads/explainer_videos'), $vidName);

        }

           $insert = DB::table('videos')->insert(
				array(
					'title' => $request['title'],
					'video' => $vidName
				)
			);

	session()->flash('msg', 'Video added successfully.');
    return redirect()->back();
    }

    public function all_videos(){
        $all_videos  = DB::table('videos')->get();
		return view('admin.dashboard.admin.view_videos', compact('all_videos'));
    }


	public function video_edit($id){
		$video  = DB::table('videos')->where('id', $id)->first();
        return view('admin.dashboard.admin.video_edit', compact('video'));
	}

	public function video_update(Request $request){

		DB::table('videos')->where('id', $request['id'])->update(
				array(
					'title' => $request['title'],
					'video' => $request['video']
				)
			);
	session()->flash('msg', 'Video updated successfully.');
    return redirect(url('/all_videos'));
	}

    public function video_delete(Request $request){
		DB::table('videos')->delete($request['id']);
			session()->flash('msg', 'Video deleted successfully.');
    return redirect()->back();
	}

     public function check_order_number()
    {

        //echo 'hey there'; exit;
        // dd($_GET);
        $num_check=AddUsers::where('order_number',$_GET['number'])->get();
        if(count($num_check)>0)
        {
            echo"exists";
        }

    }

    public function checkEmpNumber(Request $request){
        try {
            $validator = \Validator::make($request->all(), [
                'userId' => 'required|exists:users,id',
                'empNumber' => 'required',
                'type' => 'required',
            ]);

            if ($validator->fails())
            {
                return response()->json([
                    'message'=> 'Please try again ,validation failed',
                    'status'=> 0
                ]);
            }

            $result = Employee::where("user_id",$request->userId)
                ->where("empNumber",$request->empNumber);

            if ($request->type == 'edit'){
                $result = $result->where("id",'<>',$request->empId);
            }

            $result = $result->exists();
            return response()->json([
                'message'=> $result ? "The Employee ID Number $request->empNumber is already exists" : '',
                'status'=> $result ? 0 : 1
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'message'=> 'Please try again ,validation failed',
                'status'=> 0
            ]);
        }
    }

    public function manage_uploads(){
        $all_downloads  = DB::table('downloads')->where('category', 'Emergency Signs')->get();
		return view('admin.dashboard.admin.view_downloads', compact('all_downloads'));
    }
    
    
    public function add_download(Request $request){
       // dd($request->all());
        $path='';
        if( $request->hasFile('file') ) {
            $file = $request->file('file');
            // Get the Image Name
            $fileName = time().'.'.$file->getClientOriginalExtension();
            // Set the Filepath 
            $path = public_path('uploads/downloads') ;
            // Move the file to the upload Folder
            $file = $file->move($path, $fileName);
        }
        if( $request->hasFile('file2') ) {
            $file = $request->file('file2');
            // Get the Image Name
            $fileName2 = rand().'.'.$file->getClientOriginalExtension();
            // Set the Filepath 
            $path = public_path('uploads/downloads') ;
            // Move the file to the upload Folder
            $file = $file->move($path, $fileName2);
        }
        if( $request->hasFile('thumbnail') ) {
            $file = $request->file('thumbnail');
            // Get the Image Name
            $thumbnail = rand().'.'.$file->getClientOriginalExtension();
            // Set the Filepath 

            $path = public_path('uploads/downloads') ;
            // Move the file to the upload Folder
            $file = $file->move($path, $thumbnail);
        }
   
       $insert = DB::table('downloads')->insert(
            array(
                'name' => $request['name'],
                'category' => $request['category'],
                'des' => $request['description'],
                'download_file' => $fileName,
                'download_file2' => $fileName2,
                'thumb_nail' => $thumbnail,
                'user_type' => $request['user_type']
            )
        );

    session()->flash('msg', 'Download added successfully.');
    return redirect()->back();
    }

    public function download_delete(Request $request){
		DB::table('downloads')->delete($request['id']);
        UserDownload::where('download_id',$request['id'])->delete();
		session()->flash('msg', 'Download deleted successfully.');
    return redirect()->back();
	}

    public function viewUsersDownloads(){
        // $view_downloads  = UserDownload::with('downloads')->get();
        $users = User::with('userDownload', 'userDownload.downloads')->get();
		return view('admin.dashboard.admin.view_users_downloads', compact('users'));
    }

    public function filter(Request $request)
    {
    $category = $request->category;
    $downloads = DB::table('downloads')->where('category', $category)->get();

    // Return the filtered data as a view partial
    return view('admin.dashboard.admin.view_category_downloads', compact('downloads'));
    }

}