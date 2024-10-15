<x-app-layout>
    <head>
        <!-- Add CSRF Token Meta Tag -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/dental.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

    <body>
        @if ($dentalRecord)
            <input type="hidden" id="dental-record-id" name="dental_record_id" value="{{ $dentalRecord->dental_record_id }}">
        @else
            <p>No dental record found for this user. A new dental record will be created automatically.</p>
        @endif

        <div class="main-container">
            <!-- Left side: Dental Record and Update -->
            <div class="dental-records">
                <!-- Dental Record Tab Content -->
                <form id="dental-record-form" action="{{ route('staff.dental-record.store') }}" method="POST">
                    @csrf
                    <div id="record-tab" class="tab-content active">
                        <div class="dental-charting">
                            <h2>Dental Record</h2>
                            <div class="form-section-inline">
                                <!-- ID Number -->
                                <label for="id_number">ID Number:</label>
                                <input type="text" id="id_number" name="id_number" value="{{ $personInfo->id_number ?? 'Unknown' }}" class="form-control" readonly>

                                <!-- Patient Name -->
                                <label for="patient_name">Name:</label>
                                <input type="text" id="patient_name" name="patient_name" value="{{ $dentalRecord->patient_name ?? $personName }}" class="form-control" readonly>

                                <!-- Role-Specific Information -->
                                <label for="additional-info">
                                    @if($user->role === 'student')
                                        Grade & Section:
                                    @elseif($user->role === 'teacher')
                                        Department:
                                    @else
                                        Position:
                                    @endif
                                </label>
                                <input type="text" id="additional-info" name="additional_info" value="{{ $additionalInfo }}" class="form-control" readonly>
                            </div>

                            <div class="svg-container">
                                <div class="labels">
                                    <span class="upper-left-label">Upper Left</span>
                                    <span class="upper-right-label">Upper Right</span>
                                    <span class="lower-left-label">Lower Left</span>
                                    <span class="lower-right-label">Lower Right</span>
                                </div>
                                <svg class="diagram" viewBox="0 0 300 400">
                                <path
    class="tooth-11 tooth-11-parent"
    d="m 113.894,31.723601 c 0.0561,0.43476 3.08165,4.91178 3.84449,6.93412 1.03137,2.18327 2.67371,4.15697 7.0469,5.19412 3.57083,-0.36803 7.19248,-0.4467 10.19825,-4.03315 l 7.38989,-9.40518 1.34756,-2.99193 c 0.97308,-2.16029 -1.13419,-4.14679 -3.10702,-4.99829 l -5.34936,-1.19716 c -3.12438,-0.16807 -5.19809,-0.93656 -11.30278,0.59905 l -5.72815,1.04816 c -2.08382,0.77109 -4.86648,0.46927 -4.92056,4.35665 0.10953,1.48595 -0.58405,2.8577 0.58078,4.49361 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-11"
    d="m 119.37781,37.475811 -1.30961,-9.17465 c 0.71031,0 -0.79931,-1.85218 1.86701,-2.67885 9.73684,-3.18201 15.36382,-0.84956 16.95192,-0.1499 1.58809,0.69959 2.96678,2.61285 2.6621,4.62294 -0.30463,2.01002 -0.97137,2.49278 -1.42348,3.49091"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-12 tooth-12-parent"
    d="m 91.428666,35.605041 c 11.503464,-6.33738 9.146764,-4.4876 14.070254,-5.89646 1.71617,-0.51474 3.14074,-0.59168 3.86485,0.38286 l 2.6696,2.25199 c 1.81413,1.91332 1.6934,2.3195 1.92366,2.99912 0.8546,5.9162 -0.13307,5.84195 -0.32349,8.35998 -1.31549,2.1432 -2.9041,4.05602 -5.59189,5.04156 -1.65863,0.98199 -3.95557,0.88559 -6.39559,0.54752 l -4.012326,-0.81993 c -1.573083,0.19851 -2.928476,-0.68202 -4.307691,-1.44457 -2.910666,-1.71458 -3.662865,-4.14821 -4.663646,-6.49914 -0.201289,-1.52053 0.314192,-2.86745 1.499619,-4.05225 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-12"
    d="m 94.666436,46.343741 c -1.544027,-2.01495 -4.015778,-3.64326 -1.017236,-7.55177 2.750396,-1.80114 4.902858,-2.35706 7.29674,-3.41563 2.06063,-0.87054 4.10556,-1.71496 5.58118,-1.60995 6.1448,-0.49504 3.61491,0.73686 5.2752,1.13465"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-12"
    d="m 107.55706,37.262661 c 0.52599,2.30909 1.01611,4.67803 2.51107,5.37139"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-12"
    d="m 104.03277,46.863471 c 0.2176,-1.96646 -3.19877,-2.7984 -6.010321,-3.81921"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>	
  <path
    class="tooth-13 tooth-13-parent"
    d="m 76.924949,61.279161 c -4.661053,-1.305 -6.843883,-3.69247 -7.339272,-6.81701 -0.575848,-3.05499 0.06037,-6.03463 2.258302,-8.91722 1.922291,-4.48919 3.829829,-4.24058 5.739016,-4.5421 1.703054,0.18022 3.25096,0.0983 4.758501,-0.0522 4.556612,-0.16942 6.253977,1.56471 7.352032,2.69905 3.845015,4.32077 3.420426,6.83837 4.35558,9.93011 0.481064,3.41383 0.268826,6.33289 -1.809063,7.91994 -6.322272,3.96823 -7.396961,2.02387 -10.042838,1.84972 -4.927107,-1.74143 -3.659851,-1.42841 -5.272258,-2.07053 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-13"
    d="m 74.039227,56.842881 c 0.221473,-4.22581 0.644762,-8.23493 3.005608,-10.16346 2.336081,-2.05381 5.341768,-3.54265 9.455081,-4.0972"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-13"
    d="m 88.421251,46.330051 c 2.787923,9.10135 3.996541,11.24926 -3.288822,5.60813"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-13"
    d="m 81.92554,55.113251 c 6.138064,6.23387 2.066664,5.31188 -4.543407,2.61052"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-14 tooth-14-parent"
    d="m 64.287549,60.689891 c -7.036983,2.05655 -7.499595,4.89403 -7.533489,7.78258 -0.357912,12.705 12.493542,12.48996 14.982456,11.51324 3.915814,0.40697 6.635348,0.029 8.775402,-0.72941 3.996026,-0.2573 5.920727,-2.26187 6.559363,-5.35139 0.584996,-1.65849 0.784388,-3.47976 -0.204908,-5.80303 -0.723248,-1.2977 -0.231398,-2.54169 -4.671496,-4.00347 -4.681827,-0.43301 -6.163843,-1.42956 -8.096137,-2.51347 -2.779381,-2.5312 -6.236813,-1.97896 -9.811191,-0.89505 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-14"
    d="m 70.978866,60.704571 c 0.0319,0.62403 0.571799,1.24913 -1.269769,1.86896 -3.189123,2.1702 -2.973255,3.77656 -2.247001,5.29849 1.476584,2.35431 0.950066,3.46905 -0.532899,3.99204 -4.599213,3.74372 -3.551609,4.51177 -4.778427,6.47835"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-14"
    d="m 65.279354,76.415121 c 4.29831,-0.5114 7.758754,-1.93772 8.717783,-6.09536 0.507031,-2.40736 -1.153684,-3.78149 -3.404816,-4.87414"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-14"
    d="m 80.848615,66.653581 c -5.617237,0.47275 -7.424129,1.68252 -6.70124,3.16737 -0.01555,3.9172 1.465284,2.98769 2.514615,4.24825"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-14"
    d="m 71.523837,76.678431 c 0.797156,-0.87177 0.530229,-1.74585 0.156881,-2.62015"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-14"
    d="m 76.168244,65.520251 c -1.961012,1.51323 -2.158947,2.29157 -2.165412,2.99014"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-14"
    d="m 82.63766,65.160111 c 1.377059,2.5781 0.0085,4.69009 0.27693,6.55188 0.256583,2.59532 -0.660889,4.80462 -3.959888,6.23063 -2.028464,0.95862 -4.49012,1.15425 -6.695376,1.29552"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-15 tooth-15-parent"
    d="m 57.473765,80.464991 c -8.431027,1.00936 -12.429637,4.65891 -9.877252,12.21083 2.688393,2.77158 5.132545,5.74701 9.695968,6.95317 1.616986,-0.0283 3.036904,0.10824 4.006631,0.620389 1.399996,0.32137 3.003957,0.31919 4.73703,0.11232 3.263724,-1.454589 7.652073,-0.2444 9.490541,-5.075989 1.517631,-3.86591 1.258553,-7.27018 -2.398877,-9.79138 -7.228529,-5.07305 -11.201614,-4.64639 -15.654041,-5.02934 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-15"
    d="m 64.017607,99.560231 c 3.218535,-1.83743 7.516836,-0.29878 8.940041,-5.84531 0.251569,-1.76849 1.693998,-3.85582 -1.610955,-7.92747"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-15"
    d="m 59.537149,80.979141 c -1.068561,1.04034 -2.062091,2.01914 -1.663212,3.13974 -0.465371,1.29699 -0.463993,1.67812 -0.539801,2.15625 -0.660628,1.03004 -0.710131,1.29501 -0.733588,1.52747 0.02241,1.78692 0.379987,2.18359 0.720566,2.65202 0.962308,2.36493 0.08107,2.86361 -0.497281,3.66865 -0.586201,0.7973 -1.405345,0.40277 -1.708838,2.64665"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-15"
    d="m 65.803025,97.201131 c -4.094686,-1.19002 -4.354798,-3.01483 -3.628538,-5.00291 -0.431478,-3.19923 1.120741,-5.05945 2.209051,-7.23283"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-15"
    d="m 68.144634,86.400831 c -1.701079,0.17297 -3.401807,0.21793 -5.104514,0.90622"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-15"
    d="m 60.255473,84.344691 c 1.8564,1.33738 2.431475,2.4029 3.025951,3.47262"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-15"
    d="m 57.544054,96.877191 c 0.78275,-0.67 2.222159,-1.66864 4.62569,-3.14996"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-16 tooth-16-parent"
    d="m 40.400929,101.93638 c 8.540214,-6.220469 14.83636,-2.627509 21.132851,0.9639 1.70039,1.7707 3.363687,3.5413 5.692529,5.31326 7.131417,5.75158 5.79007,9.65482 1.660196,12.94987 -2.573952,2.39643 -5.039142,4.74748 -6.337117,6.61203 -1.48762,1.28541 -2.855361,2.27152 -4.017065,2.7435 -5.497444,2.07161 -7.596361,-0.81763 -10.682339,-2.26609 -11.087339,-4.90405 -15.057835,-11.73539 -12.204887,-20.4145 0.31436,-3.34607 2.189645,-4.99871 4.755832,-5.90197 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-16"
    d="m 41.71356,109.48273 c -1.793872,-6.97856 2.534794,-6.20622 4.883559,-7.94042 4.080457,-0.82336 7.498474,-2.828319 14.329591,1.25469 5.087701,5.94121 3.566612,9.2765 4.209478,13.4651 0.314098,3.99921 -1.116603,6.42981 -3.059932,7.43475 -0.483572,2.15731 -0.369384,4.22178 -4.634918,6.10614 -3.630005,2.71627 -6.181271,0.37991 -8.863197,-1.34468 -4.038369,-0.91091 -4.687008,-3.13754 -4.596452,-6.2283 -8.218179,-2.99932 -3.622847,-8.66354 -2.268129,-12.74728 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-16"
    d="m 57.646425,102.99366 c -0.835531,0.82319 -1.950837,0.9361 -2.196631,3.25721 0.377749,2.37943 -1.179557,4.75452 -2.694602,7.12975 -1.566707,1.27953 -1.058127,4.7477 -1.480204,7.23439 1.534795,1.22758 3.073385,1.24141 4.612322,1.13147 l 2.90866,2.14709"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-16"
    d="m 43.289755,110.40366 c 1.041394,0.9471 1.818882,2.04093 4.731337,1.94719 1.660625,-0.17834 3.320994,-0.2683 4.978341,0.62258"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-16"
    d="m 61.629168,111.25942 c -3.1824,0.45917 -7.886313,0.27574 -8.751252,1.71372"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-16"
    d="m 45.076555,121.72273 c 3.240786,1.23084 4.613615,-0.0607 6.31926,-0.90363"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-16"
    d="m 47.737092,125.50026 1.469421,-3.76848"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-17 tooth-17-parent"
    d="m 28.730841,143.70545 c -1.738504,-1.99931 -1.511164,-4.90954 -0.338594,-8.2577 4.474246,-8.60052 12.512518,-10.45413 25.03487,-3.81872 3.92789,1.33064 7.041725,3.88921 9.09019,6.4421 2.015003,2.51132 3.885891,3.72014 2.889861,8.1614 -2.299784,7.48128 -6.272087,13.34988 -17.529844,12.19412 -4.473038,-0.45662 -8.42318,0.5263 -14.080605,-3.19104 -2.190077,-3.04198 -6.410162,-2.83939 -5.065878,-11.53016 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-17"
    d="m 51.953899,136.62079 c -1.14411,0.52177 -1.969033,0.51149 -2.795422,0.50354 -2.206896,-0.27489 -3.134363,0.70956 -3.408443,2.33709 -0.02932,1.02339 0.296331,2.14687 -1.587405,2.64692 -1.623112,1.24226 -0.813367,1.8099 -0.615265,2.54712 -0.04397,1.15672 -0.08702,1.93304 -0.129451,2.54814 -0.03708,4.15222 1.318064,4.06319 2.169292,5.50948 0.512891,0.46885 1.14687,0.90411 0.723758,1.63258 l 0.969465,0.71573"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-17"
    d="m 44.438005,131.71125 c 0.463561,0.85049 0.914273,1.70099 1.692621,2.55219 0.864338,0.54275 1.229578,1.71024 1.448121,3.0613"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-17"
    d="m 35.808271,133.42526 c 1.634924,0.0586 3.272954,0.11453 3.640267,1.23126 2.07132,1.28611 4.148849,2.57608 6.425514,3.98972"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-17"
    d="m 32.74256,142.69487 4.735737,0.52011 c 1.781367,0.64501 3.647339,1.07868 6.073208,0.11535"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-17"
    d="m 42.056984,155.15179 1.217504,-0.91476 c -0.337126,-2.61551 0.281241,-3.00781 1.223024,-2.64766"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-17"
    d="m 43.546071,145.06322 2.551524,-0.19829 c 1.131692,0.65507 2.264161,1.04481 3.396715,1.43462"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
	class="tooth-18 tooth-18-parent"
    d="m 41.96617,158.28623 c 4.957642,0.3802 9.428351,1.37009 12.439384,3.64608 4.298567,1.86448 7.041035,3.81871 6.814214,5.94445 1.375849,3.24006 0.304958,5.59378 -0.500905,8.0435 l -2.290119,4.0215 c -1.448553,2.34064 -4.442078,3.89867 -9.124602,4.60116 -5.51245,0.76681 -11.025416,1.68656 -16.527257,-0.94524 -6.263892,-1.96088 -6.561951,-4.74265 -7.163588,-7.48272 -1.848724,-2.81074 -3.086495,-6.19523 -2.353337,-11.43077 0.649676,-2.39317 1.475289,-5.43564 5.517882,-6.82619 4.04251,-1.39056 7.66734,-0.66913 13.188328,0.42823 z"
	style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
	class="tooth-18"
	d="m 27.214414,162.56067 c -4.602491,5.48162 -1.449069,7.89644 0.452177,10.80646 -0.517377,4.93338 -0.362742,8.72622 3.618361,8.26497 5.869328,3.50971 10.149782,1.62584 14.816091,1.05191 0.669255,-0.89117 0.711772,-2.04702 2.558513,-2.44089 0,0 4.110124,-7.39848 4.642593,-8.55257 0.532469,-1.1542 0.961964,-3.2939 -1.804566,-5.61058 0,0 -4.280284,-3.75371 -7.030341,-4.80657 -1.308061,-0.50084 -2.848462,-0.34979 -4.249495,-0.62096 -2.872351,-0.55593 -8.496574,-2.05745 -8.496574,-2.05745 -2.69486,-0.49983 -4.370838,0.6715 -4.506759,3.96568 z"
	style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
	class="tooth-18"
	d="m 46.117259,177.48517 c 0.175593,-0.0976 -2.921599,-1.75352 -5.09408,-2.45767 -1.110909,-0.36007 -1.775158,-1.48015 -2.057348,-2.451 -0.580852,-1.99823 1.725136,-4.53333 0.990856,-6.11418 -0.734366,-1.58083 -2.277959,-1.65191 -3.637421,-2.14869 -0.910132,-0.33257 -1.450364,-0.055 -2.913751,-0.51604"
	style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-18"
    d="m 44.335288,164.63708 c -1.936175,0.47834 -4.395158,0.8824 -4.499429,1.62115"
	style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;marker-start:none;marker-mid:none"/>
  <path
    class="tooth-18"
    d="m 33.619229,173.17633 c 0.782922,-0.32756 1.790594,-0.72316 3.038028,-0.40109 1.24743,0.32207 1.898657,0.42253 2.309522,-0.50459"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-18"
    d="m 44.189019,172.58793 c -1.823454,-0.063 -3.816982,0.85557 -5.341772,-0.92916"
	style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-18"
    d="m 33.867266,132.60545 c 4.711244,-8.54627 12.420066,-1.7517 19.908966,3.91744 1.532555,1.54557 3.478301,2.51657 4.113488,5.30978 2.308317,4.92518 -3.415862,7.7301 -6.108223,11.09791 -2.692275,3.3677 -5.472087,5.04721 -10.336673,3.74897 -11.375911,-3.51898 -11.810579,-8.13329 -10.041632,-12.96823 -3.592488,-7.15608 -1.159117,-7.53274 -0.213023,-9.58263 0.892108,-0.41319 1.783438,-0.60833 2.677097,-1.52324 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-18"
    d="m 40.051296,163.38475 c 0.439499,0.91238 0.673738,1.78972 -0.09978,2.49567"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-21 tooth-21-parent"
    d="m 175.14525,31.757761 c -0.0561,0.43475 -3.08166,4.91178 -3.84449,6.93411 -1.0314,2.18329 -2.67373,4.15698 -7.0469,5.19413 -3.57085,-0.36803 -7.1925,-0.4467 -10.19825,-4.03314 l -7.38988,-9.40519 -1.34757,-2.99194 c -0.9731,-2.16026 1.13418,-4.14677 3.10702,-4.99829 l 5.34936,-1.19716 c 3.12437,-0.16804 5.19808,-0.93654 11.30286,0.59906 l 5.72806,1.04815 c 2.08381,0.77109 4.86648,0.46928 4.92055,4.35667 -0.10952,1.48594 0.58404,2.85768 -0.58076,4.4936 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-21"
    d="m 169.66144,37.509981 1.30961,-9.17466 c -0.71032,0 0.7993,-1.85217 -1.86701,-2.67886 -9.73684,-3.182 -15.36382,-0.84955 -16.95192,-0.14988 -1.5881,0.69959 -2.96678,2.61284 -2.6621,4.62295 0.30462,2.01002 0.97136,2.49275 1.42345,3.49089"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-22 tooth-22-parent"
    d="m 197.61055,35.639201 c -11.50344,-6.33737 -9.14675,-4.48759 -14.07014,-5.89645 -1.71628,-0.51475 -3.14085,-0.59168 -3.86493,0.38286 l -2.6696,2.25197 c -1.81414,1.91334 -1.69341,2.3195 -1.92368,2.99912 -0.8546,5.91623 0.13307,5.84197 0.32351,8.35999 1.31548,2.14319 2.90408,4.05601 5.59188,5.04157 1.65864,0.98198 3.95557,0.88558 6.39568,0.54751 l 4.01223,-0.81994 c 1.57309,0.19854 2.92847,-0.682 4.30771,-1.44456 2.91064,-1.71458 3.66285,-4.14822 4.66364,-6.49912 0.20138,-1.52056 -0.3142,-2.86746 -1.49962,-4.05227 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-22"
    d="m 194.37281,46.377911 c 1.54403,-2.01495 4.01577,-3.64326 1.01732,-7.55179 -2.75048,-1.80114 -4.90296,-2.35706 -7.29682,-3.41563 -2.06065,-0.87054 -4.10556,-1.71494 -5.5812,-1.60991 -6.14478,-0.49508 -3.61489,0.73684 -5.27519,1.13463"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-22"
    d="m 181.48219,37.296831 c -0.52601,2.30907 -1.01613,4.67802 -2.51109,5.37138"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-22"
    d="m 185.00647,46.897631 c -0.2176,-1.96645 3.19887,-2.79841 6.0103,-3.8192"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-23 tooth-23-parent"
    d="m 212.11427,61.313331 c 4.66114,-1.30502 6.84388,-3.69249 7.33927,-6.81702 0.57587,-3.05499 -0.0603,-6.03464 -2.25828,-8.91722 -1.92229,-4.48919 -3.82983,-4.24058 -5.73902,-4.54209 -1.70306,0.18022 -3.25096,0.0983 -4.75842,-0.0522 -4.5566,-0.16945 -6.25405,1.56469 -7.35212,2.69903 -3.84499,4.32077 -3.42041,6.83837 -4.35558,9.93012 -0.48096,3.41382 -0.26881,6.33281 1.80909,7.91996 6.32226,3.96822 7.39694,2.02383 10.04283,1.84969 4.9271,-1.74142 3.65983,-1.4284 5.27223,-2.07051 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-23"
    d="m 215.00002,56.877051 c -0.22149,-4.22583 -0.64477,-8.23494 -3.00562,-10.16346 -2.33609,-2.05383 -5.34176,-3.54266 -9.455,-4.09721"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-23"
    d="m 200.61798,46.364221 c -2.78783,9.10135 -3.99653,11.24924 3.28882,5.60813"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-23"
    d="m 207.1137,55.147401 c -6.13808,6.23388 -2.06668,5.31188 4.54339,2.61055"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24 tooth-24-parent"
    d="m 224.75169,60.724041 c 7.03697,2.05657 7.49958,4.89405 7.53348,7.78258 0.35791,12.70499 -12.49354,12.48997 -14.98244,11.51326 -3.91582,0.40698 -6.63535,0.029 -8.77541,-0.72941 -3.99602,-0.25729 -5.92072,-2.2619 -6.55937,-5.35139 -0.585,-1.65851 -0.78439,-3.47976 0.20492,-5.80302 0.72324,-1.2977 0.23147,-2.54171 4.67149,-4.00347 4.68183,-0.43303 6.16383,-1.42957 8.09613,-2.51347 2.77947,-2.5312 6.23681,-1.97897 9.8112,-0.89508 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24"
    d="m 218.06036,60.738741 c -0.0319,0.62401 -0.57179,1.24913 1.26978,1.86895 3.18911,2.1702 2.97324,3.77656 2.24699,5.29848 -1.47657,2.35432 -0.95007,3.46905 0.5329,3.99205 4.59921,3.74369 3.55163,4.51176 4.77844,6.47835"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24"
    d="m 223.75987,76.449301 c -4.2983,-0.51142 -7.75877,-1.93772 -8.71777,-6.09536 -0.50705,-2.40737 1.15368,-3.7815 3.40482,-4.87417"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24"
    d="m 208.19063,66.687721 c 5.61724,0.47279 7.4241,1.68254 6.70123,3.1674 0.0155,3.91721 -1.46528,2.98767 -2.51461,4.24825"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24"
    d="m 217.51538,76.712591 c -0.79714,-0.87176 -0.53022,-1.74585 -0.15685,-2.62016"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24"
    d="m 212.87098,65.554401 c 1.96102,1.51324 2.15897,2.29157 2.16542,2.99014"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24"
    d="m 206.40158,65.194271 c -1.37707,2.57808 -0.008,4.69007 -0.27693,6.55189 -0.25648,2.59533 0.66089,4.80461 3.95989,6.23062 2.02854,0.95861 4.49012,1.15426 6.69537,1.29552"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-24"
    d="m 225.02162,99.594371 c -3.21853,-1.83738 -7.51683,-0.29875 -8.94004,-5.84528 -0.25158,-1.76851 -1.69398,-3.85583 1.61096,-7.92747"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-25 tooth-25-parent"
    d="m 231.56547,80.499151 c 8.43103,1.00936 12.42965,4.65893 9.87724,12.21084 -2.68839,2.77156 -5.13256,5.747 -9.69595,6.95315 -1.617,-0.0283 -3.03691,0.10824 -4.00664,0.620389 -1.39999,0.32136 -3.00395,0.31919 -4.73702,0.11232 -3.26373,-1.454619 -7.65208,-0.24442 -9.49055,-5.075989 -1.51762,-3.86591 -1.25854,-7.27018 2.39888,-9.7914 7.22853,-5.07305 11.20162,-4.64639 15.65404,-5.02933 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-25"
    d="m 229.50209,81.013321 c 1.06855,1.04032 2.06208,2.01913 1.66319,3.13973 0.46539,1.29698 0.46401,1.67811 0.53981,2.15622 0.66065,1.03005 0.71015,1.29504 0.7336,1.5275 -0.0224,1.7869 -0.37999,2.18358 -0.72057,2.65199 -0.96231,2.36497 -0.081,2.86364 0.49728,3.66867 0.58621,0.7973 1.40534,0.40278 1.70884,2.64664"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-25"
    d="m 223.23621,97.235281 c 4.09468,-1.19 4.35479,-3.01479 3.62853,-5.0029 0.43149,-3.19923 -1.12073,-5.05943 -2.20904,-7.23282"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-25"
    d="m 220.89459,86.435001 c 1.70108,0.17298 3.40182,0.21791 5.10452,0.90621"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-25"
    d="m 228.78376,84.378861 c -1.85639,1.33738 -2.43148,2.40289 -3.02595,3.47261"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-25"
    d="m 231.49517,96.911331 c -0.78273,-0.66998 -2.22215,-1.66862 -4.62568,-3.14995"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-26 tooth-26-parent"
    d="m 248.63831,101.97056 c -8.54022,-6.220499 -14.83636,-2.627539 -21.13285,0.9639 -1.70031,1.77067 -3.36369,3.54129 -5.69255,5.31326 -7.1314,5.75156 -5.79006,9.65479 -1.66018,12.94984 2.57395,2.39645 5.03916,4.74751 6.3371,6.61207 1.48763,1.28539 2.85536,2.27151 4.01709,2.74345 5.49743,2.07163 7.59635,-0.81761 10.68233,-2.26607 11.08734,-4.90404 15.05783,-11.73539 12.20489,-20.41449 -0.31437,-3.34609 -2.18965,-4.99871 -4.75583,-5.90196 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-26"
    d="m 247.32567,109.5169 c 1.79388,-6.97855 -2.53479,-6.20623 -4.88354,-7.94043 -4.08046,-0.82335 -7.49848,-2.828309 -14.3296,1.25471 -5.08772,5.94118 -3.56661,9.27648 -4.20948,13.4651 -0.31409,3.99921 1.11659,6.42982 3.05993,7.43473 0.48356,2.15731 0.36938,4.22177 4.63491,6.10615 3.62999,2.71626 6.18128,0.37989 8.86319,-1.34467 4.03838,-0.91093 4.68701,-3.13757 4.59647,-6.22832 8.21826,-2.99933 3.62284,-8.66353 2.26812,-12.74727 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-26"
    d="m 231.39281,103.02781 c 0.8356,0.82321 1.95083,0.93613 2.19664,3.25722 -0.37776,2.37943 1.17955,4.75452 2.69459,7.12975 1.56671,1.27953 1.05814,4.7477 1.4802,7.23441 -1.53479,1.22756 -3.07337,1.24138 -4.61231,1.13144 l -2.90868,2.1471"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-26"
    d="m 245.74949,110.4378 c -1.04132,0.94712 -1.81888,2.04093 -4.73136,1.94721 -1.66063,-0.17833 -3.32098,-0.26828 -4.97832,0.62258"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-26"
    d="m 227.41007,111.29357 c 3.18248,0.45916 7.8863,0.27578 8.75124,1.71373"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-26"
    d="m 243.96268,121.75689 c -3.24079,1.23082 -4.6136,-0.0607 -6.31927,-0.90361"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-26"
    d="m 241.30213,125.5344 -1.46941,-3.76845"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27 tooth-27-parent"
    d="m 260.30839,143.73962 c 1.73849,-1.9993 1.51117,-4.90954 0.3386,-8.25772 -4.47424,-8.60051 -12.51244,-10.45412 -25.03488,-3.81871 -3.92788,1.33064 -7.04171,3.8892 -9.09018,6.44212 -2.01502,2.5113 -3.88589,3.72012 -2.88985,8.1614 2.29978,7.48127 6.27207,13.34987 17.52981,12.19409 4.47305,-0.45661 8.42319,0.52634 14.08064,-3.19106 2.19006,-3.04194 6.41016,-2.83937 5.06586,-11.53012 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27"
    d="m 255.17197,132.63962 c -4.71125,-8.54629 -12.42006,-1.75172 -19.90897,3.91743 -1.53256,1.54558 -3.4783,2.51656 -4.11349,5.30979 -2.30831,4.92516 3.41586,7.73009 6.10823,11.09789 2.69227,3.36772 5.47208,5.04721 10.33666,3.74899 11.37592,-3.51899 11.81059,-8.1333 10.04164,-12.96823 3.59249,-7.15611 1.15912,-7.53274 0.21302,-9.58265 -0.89211,-0.41321 -1.78344,-0.6083 -2.67709,-1.52322 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27"
    d="m 237.08534,136.65497 c 1.14411,0.52176 1.96903,0.51148 2.79542,0.50352 2.2069,-0.27488 3.13437,0.70957 3.40844,2.33708 0.0293,1.02341 -0.29633,2.14689 1.58742,2.64692 1.6231,1.24228 0.81335,1.80991 0.61525,2.54712 0.0441,1.15673 0.087,1.93303 0.12945,2.54816 0.0371,4.1522 -1.31805,4.06317 -2.16929,5.50947 -0.5129,0.46886 -1.14686,0.90412 -0.72377,1.63258 l -0.96945,0.71573"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27"
    d="m 244.60123,131.74542 c -0.46355,0.85048 -0.91427,1.70098 -1.69262,2.55217 -0.86433,0.54277 -1.22958,1.71027 -1.44813,3.0613"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27"
    d="m 253.23095,133.45942 c -1.63491,0.0586 -3.27293,0.11454 -3.64026,1.23126 -2.07131,1.28613 -4.14885,2.57607 -6.42551,3.98972"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27"
    d="m 256.29667,142.72905 -4.73575,0.52008 c -1.78134,0.64501 -3.64733,1.07869 -6.07319,0.11535"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27"
    d="m 246.98225,155.18594 -1.21751,-0.91476 c 0.33714,-2.61552 -0.28124,-3.00779 -1.22294,-2.64764"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-27"
    d="m 245.49314,145.0974 -2.5515,-0.19832 c -1.1317,0.65508 -2.26417,1.04483 -3.39671,1.43464"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-28 tooth-28-parent"
    d="m 247.07307,158.32041 c -4.95764,0.38019 -9.42836,1.37006 -12.4394,3.64607 -4.29856,1.86448 -7.04104,3.81869 -6.81422,5.94438 -1.37583,3.24013 -0.30494,5.59385 0.50092,8.04356 l 2.29013,4.0215 c 1.44855,2.34064 4.44207,3.89867 9.12459,4.60115 5.51245,0.76682 11.02542,1.68658 16.52726,-0.94521 6.2639,-1.9609 6.56194,-4.74266 7.16367,-7.48275 1.84863,-2.8107 3.08643,-6.19523 2.35325,-11.43075 -0.64967,-2.39318 -1.47528,-5.43565 -5.51788,-6.82621 -4.04251,-1.39056 -7.66733,-0.66912 -13.18832,0.42826 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-28"
    d="m 261.82483,162.59484 c 4.60248,5.4816 1.44905,7.89643 -0.45218,10.80644 0.51739,4.93338 0.36273,8.72622 -3.61837,8.26497 -5.86932,3.50965 -10.14978,1.62586 -14.81609,1.05192 -0.66926,-0.89117 -0.71177,-2.04701 -2.5585,-2.44088 0,0 -4.11013,-7.39848 -4.64261,-8.55258 -0.53246,-1.15418 -0.96197,-3.2939 1.80456,-5.61058 0,0 4.28029,-3.75371 7.03037,-4.80656 1.30812,-0.50083 2.84845,-0.34978 4.24948,-0.62098 2.87232,-0.55593 8.49657,-2.05743 8.49657,-2.05743 2.69486,-0.49984 4.37085,0.67151 4.50677,3.96568 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-28"
    d="m 242.92197,177.51932 c -0.17558,-0.0976 2.92161,-1.7535 5.09409,-2.45766 1.11089,-0.36006 1.77514,-1.48016 2.05735,-2.451 0.58086,-1.99824 -1.72516,-4.53335 -0.99087,-6.11417 0.73438,-1.58084 2.27804,-1.65192 3.63744,-2.14869 0.91012,-0.33257 1.45045,-0.0551 2.91374,-0.51604"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-28"
    d="m 244.70395,164.67124 c 1.93617,0.47834 4.39516,0.8824 4.49942,1.62116"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none;marker-start:none;marker-mid:none"/>
  <path
    class="tooth-28"
    d="m 255.42,173.21051 c -0.78292,-0.32757 -1.79059,-0.72318 -3.03801,-0.40111 -1.24745,0.32206 -1.89866,0.42253 -2.30953,-0.5046"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-28"
    d="m 244.85021,172.62209 c 1.82345,-0.063 3.81698,0.85555 5.34178,-0.92914"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-28"
    d="m 248.98794,163.41892 c -0.43951,0.91237 -0.67374,1.7897 0.0998,2.49565"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>	
  <path
    class="tooth-31 tooth-31-parent"
    d="m 160.54977,334.14743 c 1.12048,2.06569 1.91435,4.35173 3.95858,5.79383 0.91713,0.77123 1.70668,1.75807 0.6872,5.80105 -1.05812,1.68449 -2.86976,2.66996 -5.71641,2.69552 -2.296,-0.36226 -4.27243,-0.87858 -7.88277,-0.60666 -1.73834,-0.35247 -3.32273,0.12017 -5.43441,-2.23416 -0.69745,-1.11199 -1.78371,-1.82013 -0.0172,-5.4906 l 5.40852,-6.06401 c 1.46736,-0.91714 1.62785,-2.32935 5.04786,-2.50687 2.67434,0.0688 3.17576,1.4203 3.94858,2.6119 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-31"
    d="m 164.07862,345.18323 c -1.476,0.20988 -2.87348,0.612 -4.53464,0.19716 l -2.67598,0.1931 c -1.88337,0.23391 -3.5026,0.0217 -5.20456,-0.0508 -2.58005,-0.11326 -5.23809,-0.19564 -4.54029,-1.61224"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-32"
    d="m 176.89567,330.67982 c 1.90366,2.32523 7.18256,4.64292 1.21136,6.98544"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-32"
    d="m 172.44236,333.12294 -1.32152,5.36877 c -0.85933,2.5844 -1.73004,1.52026 -2.60222,0.007"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-32"
    d="m 183.67528,335.15728 c -0.13582,1.89509 0.075,3.97603 -2.73704,4.43591 -2.39095,0.69157 -5.39032,1.38449 -6.31313,2.07286 -3.95757,1.83415 -4.32668,0.87394 -5.94514,0.8866"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-32 tooth-32-parent"
    d="m 169.15853,329.13705 c 1.41294,-3.31132 4.86314,-3.34925 7.20638,-1.76285 l 3.3567,3.54903 3.95041,3.17338 c 0.57437,1.4853 2.11968,1.39374 0.3184,6.73777 -1.08323,2.89062 -3.26184,4.18223 -5.93305,4.75488 -3.4257,0.82565 -6.35877,0.29492 -8.99938,-1.04092 -1.80009,-1.55629 -4.64941,-2.29071 -3.88444,-5.85637 0.76352,-1.60354 0.79984,-1.97848 2.43778,-5.05922 0.51573,-1.49196 1.03389,-2.22737 1.5472,-4.4957 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-33 tooth-33-parent"
    d="m 195.66982,319.15822 c -4.97497,-0.5866 -9.32935,-0.60585 -10.17763,2.58052 l -2.50841,6.24485 c -1.80328,4.75974 0.0902,3.79635 0.38826,5.30256 l 3.72894,3.67301 c 1.425,1.21054 2.96041,2.35591 6.32711,2.41944 4.93625,-0.15321 9.93081,-0.21879 10.98427,-6.20109 l 0.42423,-6.98897 c -1.02363,-3.33951 -2.14401,-6.63159 -9.16677,-7.03032 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-33"
    d="m 201.7839,324.76019 c -1.46311,3.88912 -2.37921,8.349 -6.88612,9.06219 -3.50495,1.34127 -7.08857,3.50155 -10.03991,-0.91389"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-33"
    d="m 186.4869,330.78353 c 1.78172,0.88899 3.63416,1.93221 3.86433,-0.56996 0.32309,-2.60806 -1.02025,-3.8103 -1.57902,-5.67434"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-33"
    d="m 198.66109,324.70469 c -0.4168,-1.55616 -1.78161,-2.74255 -4.76859,-3.29636"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-33"
    d="m 197.1107,328.2021 c -1.01363,0.81141 -2.03175,0.15098 -3.0557,-2.36423 -0.53257,-1.91237 -1.47652,-2.42822 -2.38863,-3.05202"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-34 tooth-34-parent"
    d="m 219.94541,307.49978 c 2.89434,3.34934 3.58895,5.752 3.29547,7.72953 -0.86582,3.44067 -1.31395,7.1332 -5.62398,8.49779 -6.87838,1.92323 -8.35892,0.32715 -11.15358,-0.41218 -3.19782,-1.30479 -5.71244,-2.93919 -7.52451,-4.91258 -1.39354,-0.80801 -1.28944,-1.61934 -1.34584,-2.43039 0.21527,-1.50665 -0.17103,-2.52372 -0.30908,-3.7429 -0.21045,-2.3646 1.00583,-4.01506 1.69148,-5.93107 2.02812,-1.38579 3.51403,-3.28884 6.82931,-3.44662 l 5.72445,-0.13735 c 3.52615,-0.15511 5.93142,2.4119 8.41628,4.78577 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-34"
    d="m 218.46055,308.18937 c -1.02271,1.20696 -2.29203,2.62783 -2.96336,3.37577 -0.36074,0.53405 -0.0561,0.91027 -0.14324,1.7473 0.43001,2.17264 -0.37283,2.03195 -1.10744,2.49819 -0.74964,0.48782 -1.51903,0.94285 -2.07634,1.75158 -0.6394,1.62237 -1.54775,1.26163 -2.37447,1.50258 l -3.41844,0.50671 c -2.71203,0.6851 -3.1385,-0.36455 -4.61085,-0.42665"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-34"
    d="m 202.57288,315.52433 c -0.1782,0.8284 0.46234,1.75314 -0.95888,2.43544"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-34"
    d="m 209.93627,316.44399 -6.33943,-6.35019 c -1.33212,-1.35088 -2.35109,-1.67788 -2.45036,1.00372"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-34"
    d="m 210.9594,310.76399 c -0.62097,-1.60006 -2.36801,-3.19769 -5.36799,-4.79251 1.16188,0.34025 2.32074,0.68816 3.86631,0.0543 1.83199,-0.7536 2.70505,0.11313 3.79361,0.61554"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-35 tooth-35-parent"
    d="m 218.68832,285.85208 c -7.29884,3.04436 -7.19248,4.68134 -7.41301,7.00432 0.55256,2.78228 0.40498,3.81733 0.38791,5.17785 -0.0987,2.86 2.02408,3.61177 3.36163,5.10879 4.28916,3.60151 6.57409,2.43755 9.6004,3.03624 2.78292,-0.35969 4.47873,-0.71702 6.09323,-1.07413 4.53831,-0.92446 4.14168,-2.35554 5.86233,-3.5693 2.20613,-2.48431 2.39999,-5.6587 1.23494,-9.29928 -0.95543,-2.16577 -2.89446,-3.78548 -4.92369,-5.35502 l -4.9879,-2.11041 c -3.82923,-1.32869 -8.21619,0.49536 -9.21584,1.08094 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-35"
    d="m 225.52772,285.64986 c 1.58947,0.26831 3.51459,0.46689 4.17418,3.42252 -0.0137,1.28539 0.47816,2.28657 1.05099,3.24214 0.69185,3.08199 -1.1154,5.12227 -2.72825,7.24359 -1.25227,1.77357 -2.80206,1.95091 -4.30487,2.38039 -1.63889,0.72693 -2.86846,0.23957 -3.72159,-1.36452 -0.52186,-0.91547 -0.62984,-2.26745 -1.86631,-2.42924 -0.55852,-0.82672 -0.63251,-1.51907 -0.60164,-2.18241 0.70323,-1.10786 0.13325,-2.6399 -0.0869,-4.05537 2.88564,-0.12172 3.59481,-2.0603 3.92692,-4.31374 1.34266,-0.42673 2.63105,-0.57608 4.1575,-1.94336 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-35"
    d="m 222.07578,299.2592 c -1.71157,-1.30095 -1.68045,-3.58245 -1.65397,-5.86135 1.05174,-3.82138 2.95928,-5.64815 6.00723,-4.81749"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-35"
    d="m 218.79689,296.83309 c 0.13438,-1.96036 0.8458,-2.83879 1.77404,-3.31076"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-35"
    d="m 223.07985,287.46469 -0.21759,1.74743"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36 tooth-36-parent"
    d="m 231.8963,253.69055 c -1.73624,-0.45309 -3.64477,0.42587 -5.57161,1.44731 l -2.59439,2.50145 c -1.82794,1.45981 -2.59429,2.91733 -2.43984,4.37288 0.1661,2.53691 -0.63862,5.076 -2.65278,7.61777 -1.28839,3.1189 -2.12961,6.13255 0.10067,8.42289 1.36196,2.0793 3.01732,4.05941 6.33668,5.47669 3.4707,1.20137 6.53054,2.83605 10.71641,3.28325 2.73599,0.17769 4.95797,0.93364 9.36546,-0.76927 l 6.01172,-3.38242 c 1.01216,-0.62186 1.9762,-0.96998 3.55368,-4.81212 0.5161,-3.56981 2.49737,-6.9892 0.11489,-10.85668 l -3.81476,-7.35397 c -1.80371,-1.89024 -3.00101,-3.96699 -6.85638,-5.22598 -2.24707,-0.60845 -3.75557,-1.55716 -8.03343,-1.23024 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36"
    d="m 225.81068,257.19796 c -0.91695,1.60341 -2.34472,3.20799 -2.36411,4.80945 -0.46323,3.23201 -1.71023,5.48306 -2.9498,7.74327 -0.72463,1.57577 -1.84839,2.74421 -0.87332,6.05403 0.69213,2.07597 1.33644,4.17082 4.92297,5.10546 l 6.17809,2.29498 c 1.75998,0.69502 4.14918,0.59355 6.69225,0.29724 2.47598,-0.74677 4.95437,-0.71449 7.42353,-3.63507 0.53135,-0.87255 0.84339,-2.11085 0.58129,-4.30644"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36"
    d="m 246.60614,273.93604 c 1.1912,0.012 2.11617,-0.32628 1.92643,-2.1256 -0.27616,-3.41702 0.28305,-6.90605 -1.81613,-10.16608 0,-1.89797 0.1443,-3.87297 -1.57628,-4.80082 -2.27743,-1.39425 -3.36738,-3.79026 -8.18782,-3.03928 -2.37971,0.44301 -3.96169,0.46514 -4.75807,0.0728"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36"
    d="m 232.35451,257.55796 c 0.88442,1.8741 1.42758,3.63965 3.58714,5.91948 -0.87847,2.43987 -2.13505,3.31467 -3.40715,4.12542 -0.31702,0.68879 -0.83043,0.35189 -0.58231,3.99444 -0.31118,3.49481 -3.02518,4.69726 -4.58776,6.99816"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36"
    d="m 241.47361,273.13622 c -3.1266,-1.27831 -6.2546,-2.97199 -9.37525,-2.35034"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36"
    d="m 224.27648,265.87401 c 2.29548,2.97132 5.08469,2.77208 7.74287,3.41463"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36"
    d="m 228.87337,261.8083 c 1.84562,1.74557 3.67606,3.49495 3.58663,5.73232"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-36"
    d="m 245.07713,260.46248 c -2.12144,2.10497 -5.02605,3.29489 -9.1353,3.07734"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37 tooth-37-parent"
    d="m 249.6427,222.51738 c 2.77196,1.08131 5.60145,1.76836 8.19109,4.09996 2.14446,1.41719 3.73999,3.9688 4.11738,9.03794 0.18,3.56427 1.20938,7.0376 -1.60156,10.92233 -2.41345,1.70733 -0.93497,2.28843 -7.49304,5.19512 -3.37842,1.18776 -5.80361,3.80654 -10.99644,2.27029 -2.5121,-0.59702 -3.36048,-0.36138 -7.66372,-1.85492 -1.82316,-0.34856 -3.3334,-1.22592 -4.17359,-3.23528 -0.90486,-1.30016 -1.66098,-3.10521 -2.17395,-5.73544 0.1761,-2.2723 0.58422,-3.20718 0.95283,-4.36961 0.58948,-3.05528 0.36843,-4.32807 -0.0165,-5.24095 -0.0396,-1.539 0.32565,-3.0788 1.47253,-4.62034 0.60241,-1.66602 0.99898,-3.24142 4.51943,-5.06378 3.20569,-2.02233 5.28616,-3.54323 14.86535,-1.40532 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 243.56346,251.91792 c -1.25287,-0.50164 -1.26675,-1.37776 -5.05915,-1.11198 -0.91721,-0.0996 -1.83425,-0.11442 -2.75454,-1.17937 l -2.0107,-1.05633 -1.71393,-1.24407 c -1.3373,-1.21996 -1.64742,-2.53794 -1.20179,-3.92812 0.23491,-1.54226 0.61052,-3.40189 0.28588,-3.68185 -0.17651,-0.95614 0.17517,-2.50666 0.58198,-4.1192 -0.18671,-2.08124 -1.94592,-1.79138 0.348,-7.61279 0.14938,-1.24096 0.93109,-1.87562 2.37326,-1.87699 1.17377,-0.19389 1.48917,-0.64775 2.96886,-1.62873 1.05164,-0.52227 1.59551,-1.04339 3.93557,-1.56848 1.78499,0.18978 3.56772,-0.30823 5.35599,0.9241 l 3.12997,2.3641 c 2.52366,1.7554 2.79387,2.72871 2.98542,3.67466 0.70133,1.41271 0.42734,2.82752 0.16195,4.24238 -0.0595,1.2058 -0.55404,2.20422 -1.99885,2.74971 0.6939,0.6938 1.4727,1.02694 1.79464,3.30288 0.74084,3.95224 -0.3982,4.96535 -1.16913,6.55385 -1.50597,1.83667 -3.01345,3.21988 -4.52039,4.75183"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 239.39668,227.03219 c 1.35573,0.77566 2.41871,1.9839 2.98619,3.92423 -0.24389,1.51743 -0.6627,2.74318 -1.10299,3.93319 -0.37066,1.6412 -1.39282,1.54892 -0.87486,5.55492 0.92886,0.75624 1.15862,1.31869 0.89764,1.74504 -1.67935,3.00288 -1.09391,5.65503 1.58629,7.98287"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 236.8554,246.56688 c 0.82554,-1.50455 1.76568,-2.96119 4.15162,-3.81516 0.32927,-0.8517 1.44932,-1.19 3.56601,-0.88131"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 247.97647,236.55948 c -3.37576,-0.23574 -6.34126,-0.27187 -7.35849,0.64015"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 232.14564,238.2789 c 2.64103,-0.61542 4.4651,-1.82756 8.54572,-1.39142"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 233.10364,235.53151 3.64809,1.6142"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 247.65771,229.69695 c -2.42215,0.40048 -4.11538,0.79937 -5.34936,1.19723"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-37"
    d="m 244.44926,226.02276 c -0.96119,1.58278 -2.16419,3.16601 -1.99265,4.74626"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-38 tooth-38-parent"
    d="m 260.02613,214.38342 c 0.46121,-2.70997 2.52529,-2.96924 0.86432,-8.9241 -0.86527,-4.24874 -3.34697,-7.75805 -8.95254,-9.8385 -3.39318,-0.40535 -6.4858,-2.43565 -10.40869,0.0232 -1.14334,0.83863 -1.99526,1.28569 -2.74582,1.62823 -1.55929,1.36243 -4.59026,1.44291 -6.07587,6.62705 0,1.97558 0.57541,2.74027 -0.49868,6.98911 -0.34041,1.63729 -1.33111,2.66403 -0.20527,5.67825 1.21319,1.39411 1.10358,2.87043 4.84545,4.10733 1.02689,0.79598 9.18618,1.54712 11.82602,1.47148 2.63974,-0.0757 2.5084,0.68084 6.53478,-2.51012 1.59688,-1.69166 3.04717,-2.37808 4.8163,-5.25161 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-38"
    d="m 241.31283,197.76555 c 3.01777,-3.77512 6.38592,-2.09672 9.81568,0.53999 1.94953,1.965 2.55195,4.03584 2.32401,6.1718 -0.0837,1.28285 -0.74886,2.14997 -2.22285,2.43828 0.97284,0.45631 1.36155,1.3068 1.34583,2.43036 l -1.31796,6.49176 c -1.25659,2.67509 -2.25461,2.66091 -3.33482,3.50133 -0.57644,0.24486 -1.16013,0.48353 -1.93125,0.56583 -1.06656,-0.30188 -2.13278,-0.48687 -3.20645,-3.05027 0.90781,-0.24421 -2.86278,-1.46135 -0.0864,-3.86821 0.89418,-0.82067 -2.12927,-3.48086 -0.38948,-5.67688 0.8904,-2.41627 1.73834,-4.5041 2.43658,-5.43354 -1.87968,-0.25658 -1.31868,-1.69069 -1.94109,-2.55387"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-38"
    d="m 246.97457,201.3719 -2.22887,0.5041"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-38"
    d="m 238.21841,206.81934 c 1.18118,0.44643 2.20351,1.20608 3.9422,0.55287"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-38"
    d="m 245.45504,214.7898 c -1.38896,-0.17925 -2.42854,-0.77138 -2.83181,-2.11518"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-38"
    d="m 248.03252,206.86024 c -1.84449,-0.29863 -3.87536,-1.216 -5.35401,-0.30023"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-38"
    d="m 242.19719,219.1021 c -1.17473,-0.0305 -2.28496,0.0529 -3.64398,-0.30398 -1.9423,-1.82073 -2.63897,-1.82328 -3.87433,-2.61198 -0.23235,-0.0586 -2.68028,-2.19832 -0.90912,-5.42621 0.9781,-1.65178 1.21544,-2.22238 0.80716,-3.43346 0.96024,-2.82389 0.4396,-3.45971 0.65296,-5.18"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-41 tooth-41-parent"
    d="m 128.48946,334.11326 c -1.12046,2.06568 -1.91426,4.35175 -3.95858,5.79383 -0.91711,0.77124 -1.70668,1.75809 -0.68719,5.80108 1.05812,1.68447 2.86975,2.66995 5.7164,2.69548 2.296,-0.36222 4.27245,-0.87856 7.88278,-0.60663 1.73833,-0.35249 3.32274,0.12017 5.43442,-2.23419 0.69744,-1.11199 1.78368,-1.82009 0.0172,-5.49057 l -5.40851,-6.06402 c -1.46736,-0.91714 -1.62785,-2.32934 -5.04785,-2.50688 -2.67435,0.0688 -3.17577,1.42031 -3.9486,2.6119 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-41"
    d="m 124.96061,345.14908 c 1.476,0.20989 2.87347,0.61199 4.53463,0.19713 l 2.67599,0.19311 c 1.88339,0.23391 3.5026,0.0217 5.20456,-0.0506 2.58006,-0.11325 5.2381,-0.19555 4.54031,-1.61224"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-42 tooth-42-parent"
    d="m 119.88069,329.10289 c -1.41293,-3.31134 -4.86313,-3.34927 -7.20636,-1.76285 l -3.3567,3.54902 -3.9504,3.17338 c -0.57438,1.48532 -2.1197,1.39375 -0.31842,6.73777 1.08323,2.89062 3.26184,4.18226 5.93307,4.75488 3.42568,0.82568 6.35876,0.29492 8.99938,-1.0409 1.80007,-1.55631 4.64941,-2.29071 3.88442,-5.85637 -0.76352,-1.60357 -0.79984,-1.97848 -2.43777,-5.05922 -0.51574,-1.49195 -1.03389,-2.22737 -1.54722,-4.49571 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-42"
    d="m 112.14358,330.64571 c -1.90366,2.32517 -7.18256,4.64293 -1.21138,6.98539"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-42"
    d="m 116.59687,333.08879 1.32152,5.36876 c 0.85935,2.58439 1.73006,1.52025 2.60222,0.007"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-42"
    d="m 105.36395,335.12312 c 0.13592,1.89509 -0.075,3.97604 2.73704,4.43593 2.39094,0.69154 5.39042,1.38446 6.31314,2.07283 3.95757,1.83415 4.32669,0.87394 5.94513,0.88662"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>  
  <path
    class="tooth-43 tooth-43-parent"
    d="m 93.369417,319.12408 c 4.974972,-0.58661 9.329343,-0.60586 10.177633,2.58048 l 2.50842,6.24488 c 1.80327,4.75973 -0.0901,3.79632 -0.38828,5.30255 l -3.72892,3.673 c -1.42502,1.21056 -2.960415,2.35592 -6.327124,2.41947 -4.936255,-0.15322 -9.930806,-0.21881 -10.984278,-6.20112 l -0.424223,-6.98897 c 1.023625,-3.33948 2.144013,-6.63158 9.166772,-7.03029 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-43"
    d="m 87.255334,324.72604 c 1.463124,3.88911 2.379208,8.34898 6.886131,9.06217 3.50495,1.34128 7.088565,3.50157 10.039905,-0.91387"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-43"
    d="m 102.55232,330.74937 c -1.78171,0.88901 -3.634064,1.93223 -3.86433,-0.56995 -0.323069,-2.60805 1.020264,-3.8103 1.57903,-5.67435"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-43"
    d="m 90.37813,324.67051 c 0.416817,-1.55615 1.781636,-2.74255 4.768599,-3.29634"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-43"
    d="m 91.928528,328.16794 c 1.013631,0.8114 2.031751,0.15097 3.055717,-2.36423 0.532567,-1.91239 1.476499,-2.42821 2.388688,-3.05201"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-44 tooth-44-parent"
    d="m 69.09383,307.46563 c -2.89434,3.34933 -3.588951,5.752 -3.295461,7.72953 0.865799,3.44067 1.31392,7.1332 5.623962,8.49777 6.878388,1.92325 8.358928,0.32715 11.153582,-0.41217 3.197831,-1.3048 5.712444,-2.93918 7.524526,-4.91259 1.393515,-0.80799 1.289422,-1.61932 1.345827,-2.43038 -0.215274,-1.50663 0.171026,-2.52374 0.309092,-3.74292 0.210435,-2.36457 -1.005771,-4.01505 -1.691493,-5.93104 -2.028117,-1.3858 -3.514021,-3.28884 -6.829308,-3.44662 l -5.724439,-0.13737 c -3.526171,-0.15509 -5.931423,2.41194 -8.416288,4.78579 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-44"
    d="m 70.578693,308.15522 c 1.02268,1.20694 2.292017,2.62783 2.963336,3.37574 0.360764,0.53408 0.05606,0.9103 0.143253,1.74731 -0.430013,2.17266 0.372837,2.03196 1.107457,2.49822 0.749636,0.4878 1.51902,0.94281 2.076325,1.75155 0.639419,1.62238 1.54774,1.26165 2.374462,1.50259 l 3.418458,0.50672 c 2.71202,0.68509 3.138494,-0.36456 4.610854,-0.42667"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-44"
    d="m 86.466363,315.49015 c 0.178182,0.82841 -0.462352,1.75324 0.958866,2.43544"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-44"
    d="m 79.102958,316.40983 6.339433,-6.35019 c 1.332126,-1.35089 2.351102,-1.67788 2.450364,1.00371"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-44"
    d="m 78.079848,310.72984 c 0.620945,-1.60007 2.367997,-3.19771 5.367988,-4.79251 -1.161878,0.34023 -2.320734,0.68814 -3.86623,0.0543 -1.832077,-0.7536 -2.705128,0.11313 -3.793703,0.61555"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-45 tooth-45-parent"
    d="m 70.35092,285.81793 c 7.298827,3.04433 7.192491,4.68134 7.413014,7.00432 -0.552564,2.78227 -0.405004,3.81731 -0.387926,5.17784 0.09866,2.86007 -2.024062,3.61177 -3.361614,5.10879 -4.289083,3.60149 -6.574116,2.43754 -9.600409,3.03625 -2.78292,-0.35972 -4.478735,-0.71703 -6.093218,-1.07413 -4.538325,-0.92446 -4.14169,-2.35554 -5.862341,-3.5693 -2.20612,-2.4843 -2.399994,-5.65872 -1.234926,-9.2993 0.955408,-2.16576 2.89443,-3.78547 4.923662,-5.35501 l 4.987913,-2.1104 c 3.829233,-1.32867 8.216194,0.49533 9.215845,1.08094 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-45"
    d="m 63.511526,285.61571 c -1.589476,0.26829 -3.514611,0.46689 -4.174203,3.42251 0.01378,1.28538 -0.478138,2.28658 -1.050969,3.24214 -0.691848,3.08198 1.115393,5.12226 2.728239,7.2436 1.252261,1.77358 2.802068,1.95088 4.304864,2.38037 1.638887,0.72694 2.868472,0.23956 3.72168,-1.3645 0.521774,-0.9155 0.629756,-2.26749 1.866231,-2.42923 0.558519,-0.82675 0.632515,-1.51911 0.601639,-2.18242 -0.703146,-1.10786 -0.133247,-2.63991 0.08694,-4.05537 -2.885553,-0.12173 -3.594822,-2.06032 -3.926942,-4.31375 -1.342645,-0.42673 -2.631042,-0.57612 -4.157475,-1.94335 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-45"
    d="m 66.96344,299.22504 c 1.711591,-1.30095 1.680459,-3.58245 1.653982,-5.86136 -1.051747,-3.82136 -2.959284,-5.64814 -6.007228,-4.81749"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-45"
    d="m 70.242339,296.79893 c -0.134368,-1.96037 -0.84579,-2.8388 -1.774028,-3.31077"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-45"
    d="m 65.959389,287.43051 0.217589,1.74745"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46 tooth-46-parent"
    d="m 57.142934,253.65637 c 1.73626,-0.45306 3.644753,0.42587 5.571612,1.44732 l 2.594385,2.50144 c 1.827944,1.45984 2.594303,2.91743 2.439847,4.3729 -0.166108,2.53691 0.638632,5.07601 2.65277,7.61778 1.288396,3.11889 2.129618,6.13255 -0.100645,8.42287 -1.361969,2.0793 -3.017332,4.05943 -6.336686,5.4767 -3.470711,1.20137 -6.530559,2.83604 -10.716404,3.28325 -2.736,0.17769 -4.957988,0.93365 -9.365481,-0.76927 l -6.011715,-3.38242 c -1.012157,-0.62185 -1.976191,-0.96997 -3.553678,-4.81213 -0.516083,-3.56978 -2.497364,-6.9892 -0.114875,-10.85668 l 3.81474,-7.35397 c 1.803703,-1.89024 3.001028,-3.96698 6.856386,-5.22596 2.247086,-0.60847 3.755576,-1.55718 8.033445,-1.23024 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46"
    d="m 63.228563,257.1638 c 0.916939,1.6034 2.344712,3.20799 2.364114,4.80944 0.463217,3.23203 1.710217,5.48305 2.949795,7.74329 0.724627,1.57576 1.848472,2.7442 0.873311,6.05402 -0.692109,2.07597 -1.336435,4.1708 -4.922972,5.10548 l -6.178083,2.29497 c -1.759977,0.69501 -4.149192,0.59355 -6.692263,0.29723 -2.475976,-0.74678 -4.954281,-0.71449 -7.423529,-3.63507 -0.531349,-0.87257 -0.843378,-2.11085 -0.581282,-4.30644"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46"
    d="m 42.433092,273.90189 c -1.191199,0.012 -2.116164,-0.32628 -1.926429,-2.12562 0.276152,-3.41701 -0.283052,-6.90603 1.816123,-10.16606 0,-1.898 -0.144285,-3.87297 1.57628,-4.80085 2.277442,-1.39422 3.367393,-3.79024 8.187821,-3.03927 2.379813,0.44301 3.961786,0.46516 4.758075,0.0728"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46"
    d="m 56.684721,257.5238 c -0.884433,1.8741 -1.427596,3.63964 -3.587142,5.91946 0.878479,2.43989 2.135052,3.31468 3.407149,4.12545 0.317033,0.6888 0.830442,0.3519 0.58232,3.99441 0.311167,3.49482 3.025177,4.69727 4.587742,6.99817"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46"
    d="m 47.565638,273.10205 c 3.126599,-1.27829 6.254578,-2.97196 9.375226,-2.35035"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46"
    d="m 64.762754,265.83986 c -2.295469,2.97133 -5.084682,2.77207 -7.74289,3.41462"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46"
    d="m 60.165868,261.77413 c -1.84562,1.74557 -3.676059,3.49498 -3.586623,5.73233"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-46"
    d="m 43.962111,260.42831 c 2.121427,2.10498 5.026034,3.29491 9.135296,3.07735"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47 tooth-47-parent"
    d="m 39.396534,222.48322 c -2.771965,1.08129 -5.601454,1.76836 -8.191098,4.09996 -2.144369,1.41718 -3.739966,3.9688 -4.117368,9.03793 -0.179992,3.5643 -1.209399,7.03762 1.601548,10.92232 2.413449,1.70734 0.93497,2.28845 7.493041,5.19514 3.378429,1.18776 5.803607,3.80655 10.996439,2.27029 2.512107,-0.59703 3.36049,-0.36137 7.663715,-1.85493 1.823194,-0.34855 3.33341,-1.2259 4.173599,-3.23526 0.904871,-1.30017 1.660969,-3.10523 2.173948,-5.73544 -0.17611,-2.27231 -0.584216,-3.20719 -0.952822,-4.36964 -0.589477,-3.05526 -0.368433,-4.32807 0.01639,-5.24094 0.03967,-1.53898 -0.325572,-3.07879 -1.472528,-4.62033 -0.602413,-1.66601 -0.998962,-3.24142 -4.519438,-5.06377 -3.205686,-2.02234 -5.286144,-3.54323 -14.865336,-1.40533 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 45.475777,251.88377 c 1.252864,-0.50167 1.266749,-1.37776 5.059152,-1.112 0.917289,-0.0996 1.834234,-0.11442 2.754542,-1.17936 l 2.01069,-1.05633 1.713925,-1.24407 c 1.337298,-1.21996 1.647428,-2.53793 1.201808,-3.92813 -0.234929,-1.54226 -0.61052,-3.40189 -0.285899,-3.68183 0.176541,-0.95616 -0.17516,-2.50668 -0.581974,-4.11919 0.186718,-2.08126 1.945921,-1.79139 -0.347993,-7.6128 -0.149376,-1.24095 -0.931087,-1.87563 -2.37326,-1.87699 -1.173691,-0.1939 -1.489172,-0.64777 -2.968859,-1.62876 -1.051657,-0.52226 -1.595509,-1.04336 -3.935566,-1.56845 -1.784987,0.18977 -3.567735,-0.30826 -5.356003,0.92409 l -3.129962,2.36409 c -2.523667,1.75541 -2.79387,2.7287 -2.985416,3.67467 -0.701337,1.4127 -0.42734,2.8275 -0.161968,4.24239 0.05951,1.20576 0.554031,2.2042 1.998875,2.7497 -0.693919,0.6938 -1.472702,1.02695 -1.794649,3.30287 -0.740835,3.95224 0.398188,4.96535 1.169122,6.55387 1.50599,1.83668 3.013447,3.21985 4.520385,4.75183"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 49.642563,226.99802 c -1.355753,0.77565 -2.418709,1.9839 -2.986194,3.92423 0.243898,1.51744 0.662699,2.74321 1.102973,3.93318 0.370676,1.64121 1.39284,1.54893 0.874857,5.55495 -0.928845,0.75625 -1.158599,1.31869 -0.897625,1.74504 1.67934,3.00288 1.093916,5.65503 -1.586283,7.98285"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 52.183825,246.53271 c -0.825526,-1.50454 -1.765672,-2.96118 -4.151608,-3.81512 -0.329278,-0.85174 -1.449327,-1.19002 -3.56601,-0.88134"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 41.062765,236.52531 c 3.375757,-0.23574 6.341338,-0.27186 7.358498,0.64016"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 56.893603,238.24474 c -2.641046,-0.6154 -4.465104,-1.82755 -8.545733,-1.39143"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 55.935605,235.49734 -3.648115,1.61422"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 41.381521,229.66277 c 2.42216,0.40053 4.115386,0.7994 5.349363,1.19723"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-47"
    d="m 44.589967,225.98861 c 0.961188,1.58278 2.164203,3.16601 1.992664,4.74627"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-48 tooth-48-parent"
    d="m 29.013116,214.34926 c -0.461232,-2.70998 -2.525307,-2.96923 -0.864336,-8.9241 0.865286,-4.24874 3.346954,-7.75805 8.952544,-9.83848 3.39318,-0.40537 6.485799,-2.43566 10.408687,0.0232 1.143334,0.83862 1.995253,1.28569 2.745832,1.62824 1.55929,1.36239 4.590243,1.44287 6.075881,6.62702 0,1.97557 -0.575419,2.74031 0.49866,6.98912 0.340405,1.63729 1.331089,2.66403 0.205262,5.67825 -1.213193,1.39411 -1.103576,2.87044 -4.845438,4.10736 -1.026905,0.79597 -9.186179,1.54709 -11.82602,1.47145 -2.639752,-0.0757 -2.508402,0.68083 -6.534782,-2.51014 -1.596893,-1.69163 -3.047169,-2.37805 -4.81629,-5.2516 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-48"
    d="m 47.726398,197.73138 c -3.01776,-3.77512 -6.385928,-2.09673 -9.815675,0.54 -1.949543,1.96502 -2.551955,4.03582 -2.324011,6.17178 0.08367,1.28287 0.748854,2.15001 2.222847,2.43827 -0.97283,0.45633 -1.36153,1.30682 -1.345835,2.43039 l 1.317978,6.49175 c 1.256574,2.6751 2.254589,2.6609 3.334793,3.50135 0.576453,0.24484 1.160152,0.48353 1.931259,0.56583 1.066578,-0.30188 2.13281,-0.48688 3.206546,-3.05029 -0.907889,-0.2442 2.862691,-1.46135 0.08642,-3.86814 -0.894263,-0.82073 2.129189,-3.48091 0.38939,-5.67694 -0.89038,-2.41626 -1.738331,-4.5041 -2.436561,-5.43353 1.879771,-0.25658 1.318668,-1.69072 1.941089,-2.55386"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-48"
    d="m 42.064659,201.33773 2.228886,0.50412"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-48"
    d="m 50.820828,206.78517 c -1.181197,0.44643 -2.203532,1.20608 -3.942208,0.55289"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-48"
    d="m 43.584191,214.75565 c 1.388956,-0.17927 2.42854,-0.77137 2.831816,-2.11519"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-48"
    d="m 41.006706,206.82607 c 1.844498,-0.29862 3.875368,-1.21599 5.35402,-0.30021"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="tooth-48"
    d="m 46.842051,219.06796 c 1.174729,-0.0304 2.284947,0.0529 3.643979,-0.304 1.942297,-1.82075 2.638975,-1.82329 3.874331,-2.61197 0.232343,-0.0586 2.680288,-2.19834 0.909097,-5.42624 -0.978091,-1.65176 -1.215436,-2.22236 -0.807156,-3.43336 -0.960239,-2.82396 -0.439585,-3.45978 -0.652953,-5.18009"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  </svg>
                            </div>
                        </div>

                        <button type="button" id="save-dental-record" class="save-button" 
                            @if(isset($dentalRecord)) style="display: none;" @endif>
                            Save Dental Record
                        </button>

                        <div class="legend">
                            <h3>Legend</h3>
                            <ul>
                                <li><span class="legend-box healthy"></span> Healthy</li>
                                <li><span class="legend-box aching"></span> Aching</li>
                                <li><span class="legend-box missing"></span> Missing</li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right side: Dental History -->
            <div class="dental-examination-form">
                <div class="dental-history">
                    <h2>Dental History</h2>
                    @if($dentalRecord)
                        <a href="{{ route('staff.dentalRecord.pdf', $dentalRecord->id_number) }}" class="btn btn-primary no-spinner">Download Dental Record</a>
                    @endif

                    <!-- Patient Information -->
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th colspan="3">Patients Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Patient Name:</strong></td>
                                @if($personInfo)
                                    <td>{{ $personInfo->first_name }} {{ $personInfo->last_name }}</td>
                                @else
                                    <td>Unknown</td>
                                @endif
                            </tr>
                            <tr>
                                <td><strong>Date of Birth:</strong></td>
                                <td>{{ $patientInfo->birthdate ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Last Visit Date:</strong></td>
                                <td>{{ $lastExamination->date_of_examination ?? 'Unknown' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Previous Examinations -->
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th colspan="3">Dental Examinations History</th>
                            </tr>
                        </thead>
                        <tbody id="dental-examination-history-body">
                    
                        </tbody>
                    </table>

                    <!-- Treatments Performed -->
                   <!-- Tooth History Table -->
<table class="history-table">
    <caption>Tooth History</caption>
    <thead>
        <tr>
            <th>Tooth Number</th>
            <th>Status</th>
            <th>Notes</th>
            <th> Dental Pictures </th>
            <th>Last Updated</th>
        </tr>
    </thead>
    <tbody id="tooth-history-body">
        <!-- Populate with tooth history if available -->
    </tbody>
</table>


                    <!-- Next Scheduled Appointment -->
                    <table class="history-table">
                    <thead>
                        <tr>
                            <th colspan="2">Next Scheduled Appointment with Dr. Sarah Uy-Gan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($nextAppointment)
                            <tr>
                                <td>Date:</td>
                                <td>{{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->toFormattedDateString() }}</td>
                            </tr>
                            <tr>
                                <td>Time:</td>
                                <td>{{ \Carbon\Carbon::parse($nextAppointment->appointment_time)->format('h:i A') }}</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="2">No upcoming appointments found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        <!-- Preview Modal -->
        <!-- Modal to update tooth details, status, and upload images -->
        <div id="previewModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Tooth Details</h2>
                <form id="tooth-details-form" action="{{ route('staff.teeth.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="modal-tooth">Tooth:</label>
                        <input type="hidden" id="modal-is-first-submission" value="true">
                        @if ($dentalRecord)
                            <input type="hidden" id="dental-record-id" name="dental_record_id" value="{{ $dentalRecord->dental_record_id }}">
                        @else
                            <p>No dental record found for this user. A new dental record will be created automatically.</p>
                        @endif
                        <input type="text" id="modal-tooth" name="tooth_number" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="modal-status">Status:</label>
                        <select id="modal-status" name="status" class="form-control">
                            <option value="Healthy">Healthy</option>
                            <option value="Aching">Aching</option>
                            <option value="Missing">Missing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modal-notes">Notes:</label>
                        <textarea id="modal-notes" name="notes" class="form-control"></textarea>
                    </div>
                    <input type="hidden" id="modal-svg-path" name="svg_path" value="">

                    <!-- Image Upload Section in the Modal -->
                    <div class="form-group">
                        <label for="modal-upload-images">Upload Dental Pictures:</label>
                        <div class="custom-file-upload">
                            <label for="modal-upload-images" class="upload-label">
                                <i class="fas fa-upload"></i> Choose Images
                                <input type="file" id="modal-upload-images" name="update_images[]" class="form-control" accept="image/*" multiple>
                            </label>
                        </div>
                        <div id="image-preview-container" class="image-preview-container"></div>
                        <input type="hidden" name="is_current" value="true">
                    </div>

                    <button type="button" id="save-tooth-details" class="save-button">Save</button>
                </form>
            </div>
        </div>
    </body>

    <script>
        var storeToothUrl = "{{ route('staff.teeth.store') }}";
        var storeDentalRecordUrl = "{{ route('staff.dental-record.store') }}";
        var getToothStatusUrl = "{{ route('staff.get-tooth-status') }}"; // Pass the route to JS
        var getDentalExaminationHistoryUrl = "{{ route('staff.dental-examination.history') }}";
        var getToothHistoryUrl = "{{ route('staff.tooth-history') }}"; // Ensure this is correct
        let teethStatuses = @json($teeth->pluck('status', 'tooth_number'));
        var dentalRecordId = "{{ $dentalRecord->dental_record_id ?? '' }}"; // Ensure this is correctly set
        console.log('Teeth Statuses:', teethStatuses); 
    </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{ asset('js/dental.js') }}"></script>
</x-app-layout>
