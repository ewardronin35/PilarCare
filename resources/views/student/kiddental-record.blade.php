<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/dental.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="main-container">
    <!-- Left side: Dental Record and Update -->
    <div class="dental-records">

        <!-- Dental Record Tab Content -->
        <form id="dental-record-form" action="{{ route('student.dental-record.store') }}" method="POST">
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
                            <!-- Add SVG path data here for dental chart -->
                            <path
    class="child-tooth-51 child-tooth-51-parent"
    d="m 124.82021,113.33987 c 0.0372,0.31136 2.03838,3.51786 2.54294,4.9663 0.6822,1.56367 1.76853,2.97726 4.66113,3.72006 2.36193,-0.26358 4.75744,-0.31994 6.74559,-2.88856 l 4.88802,-6.73611 0.89133,-2.14285 c 0.64364,-1.54722 -0.75021,-2.96997 -2.05513,-3.57982 l -3.5383,-0.85742 c -2.0666,-0.1203 -3.43827,-0.67078 -7.47617,0.42903 l -3.78887,0.7507 c -1.37832,0.55227 -3.21888,0.3361 -3.25466,3.12031 0.0725,1.06423 -0.38632,2.04669 0.38412,3.21836 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>  
<path
    class="child-tooth-51"
    d="m 128.44747,117.45968 -0.86624,-6.57101 c 0.46983,0 -0.52869,-1.32654 1.23491,-1.91862 6.44042,-2.27899 10.16234,-0.60845 11.21278,-0.10742 1.05044,0.50107 1.96238,1.87137 1.76084,3.31101 -0.20149,1.43962 -0.64251,1.78532 -0.94153,2.50024"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-52 child-tooth-52-parent "
    d="m 110.54038,116.16246 c 7.26885,-5.12134 5.77969,-3.6265 8.89074,-4.76501 1.08442,-0.41599 1.98459,-0.47815 2.44213,0.30939 l 1.68688,1.81986 c 1.14633,1.54619 1.07002,1.87443 1.21553,2.42363 0.54002,4.781 -0.0841,4.72099 -0.20441,6.75586 -0.83124,1.73196 -1.83506,3.27773 -3.53342,4.07416 -1.04808,0.79357 -2.49943,0.71568 -4.04124,0.44247 l -2.53533,-0.66262 c -0.994,0.16044 -1.85046,-0.55112 -2.72196,-1.16735 -1.83919,-1.38559 -2.3145,-3.35226 -2.94688,-5.25208 -0.1272,-1.22877 0.19854,-2.31724 0.94759,-3.27469 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-52"
    d="m 112.58627,124.84061 c -0.97564,-1.62832 -2.53749,-2.94419 -0.64278,-6.10275 1.73794,-1.45551 3.09805,-1.90477 4.61068,-2.76021 1.30209,-0.7035 2.59424,-1.38589 3.52665,-1.30102 3.8828,-0.40005 2.28421,0.59546 3.3333,0.91692"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-52"
    d="m 120.73164,117.50203 c 0.33235,1.866 0.64206,3.78039 1.5867,4.3407"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-52"
    d="m 117.09124,123.9055 c 0.13748,-1.58912 -2.02127,-2.26145 -3.79782,-3.08637"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-53 child-tooth-53-parent "
    d="m 103.01062,138.38843 c -3.08304,-0.93467 -4.526843,-2.64459 -4.854532,-4.88241 -0.380883,-2.18802 0.04,-4.32209 1.493748,-6.38662 1.271494,-3.21518 2.533244,-3.03715 3.796044,-3.25309 1.12646,0.12908 2.15034,0.0704 3.14749,-0.0374 3.01396,-0.12134 4.13666,1.12068 4.86298,1.9331 2.54325,3.09457 2.26243,4.89771 2.88096,7.11205 0.31819,2.44502 0.1778,4.53569 -1.19659,5.67236 -4.18183,2.84207 -4.89268,1.4495 -6.64278,1.32478 -3.25902,-1.24726 -2.42079,-1.02305 -3.48732,-1.48294 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-53"
    d="m 100.39514,134.53356 c 0.1465,-3.02657 0.42649,-5.89795 1.98804,-7.27916 1.5452,-1.47098 3.53329,-2.53728 6.25402,-2.93446"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-53"
    d="m 108.49459,127.00418 c 1.84406,6.51847 2.6435,8.05682 -2.17536,4.01659"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-53"
    d="m 106.18196,133.29479 c 4.06,4.46477 1.36698,3.80443 -3.00522,1.86967"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-54 child-tooth-54-parent "
    d="m 89.282654,139.5966 c 5.648888,-4.45518 9.813436,-1.88186 13.978216,0.69037 1.12472,1.26818 2.22491,2.53628 3.76531,3.80542 4.71702,4.11931 3.8298,6.91486 1.09811,9.27483 -1.70251,1.71634 -3.33311,3.4002 -4.19163,4.7356 -0.98399,0.92064 -1.88868,1.62688 -2.65708,1.96492 -3.636271,1.48371 -5.024579,-0.58561 -7.06579,-1.623 -7.33366,-3.51233 -9.959925,-8.40502 -8.072869,-14.62108 0.207935,-2.3965 1.448348,-3.58013 3.145733,-4.22706 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-54"
    d="m 90.150883,145.00138 c -1.186546,-4.99812 1.676616,-4.44496 3.230208,-5.68702 2.699015,-0.58969 4.959824,-2.02567 9.478229,0.89864 3.36526,4.25514 2.35913,6.64392 2.78435,9.64383 0.20776,2.86428 -0.73857,4.60513 -2.02398,5.32485 -0.31985,1.54509 -0.24432,3.02368 -3.06573,4.3733 -2.401058,1.94542 -4.088587,0.27208 -5.862521,-0.96309 -2.671184,-0.65241 -3.10021,-2.24715 -3.040305,-4.46077 -5.435886,-2.14817 -2.396319,-6.20491 -1.500251,-9.12974 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-54"
    d="m 100.6896,140.35381 c -0.55264,0.5896 -1.290369,0.67048 -1.452949,2.33289 0.249861,1.70416 -0.780214,3.40521 -1.782327,5.10638 -1.036306,0.91642 -0.699891,3.40036 -0.979079,5.18135 1.015171,0.87922 2.03288,0.88911 3.050798,0.81037 l 1.923927,1.53778"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-54"
    d="m 91.193447,145.66094 c 0.68882,0.67833 1.203096,1.46173 3.129528,1.39462 1.0984,-0.12772 2.19665,-0.19216 3.292888,0.44589"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-54"
    d="m 103.32397,146.27386 c -2.105,0.32886 -5.216376,0.19749 -5.788465,1.22738"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-54"
    d="m 92.375329,153.76781 c 2.143597,0.88152 3.051647,-0.0435 4.179828,-0.64719"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-54"
    d="m 94.135111,156.47331 0.971964,-2.69902"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55 child-tooth-55-parent "
    d="m 76.99988,167.95696 c -1.149931,-1.43191 -0.999555,-3.51625 -0.223961,-5.91426 2.959466,-6.15977 8.276337,-7.48734 16.559191,-2.735 2.598099,0.95303 4.657723,2.7855 6.012653,4.61391 1.332827,1.79864 2.570317,2.66442 1.911497,5.84527 -1.521172,5.35819 -4.148659,9.56132 -11.595024,8.73357 -2.958672,-0.32704 -5.57148,0.37695 -9.313547,-2.28547 -1.448637,-2.17867 -4.239982,-2.0336 -3.350809,-8.25802 z"
	style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55"
    d="m 92.360652,162.88288 c -0.756768,0.37367 -1.30241,0.36632 -1.849015,0.3606 -1.459731,-0.19685 -2.073195,0.50821 -2.25449,1.67385 -0.01932,0.73297 0.196014,1.53762 -1.049981,1.89579 -1.073609,0.88971 -0.538007,1.29625 -0.406966,1.82425 -0.02903,0.82846 -0.0576,1.38445 -0.08563,1.825 -0.02447,2.97386 0.871829,2.91011 1.434868,3.94594 0.339251,0.3358 0.758596,0.64756 0.478742,1.16928 l 0.641237,0.51262"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55"
    d="m 87.389301,159.36659 c 0.306624,0.60915 0.604758,1.21826 1.119579,1.82792 0.571715,0.38871 0.813305,1.22489 0.957866,2.19253"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55"
    d="m 81.681215,160.59418 c 1.081407,0.042 2.164885,0.0821 2.40785,0.88184 1.370035,0.92114 2.74421,1.84501 4.250115,2.85748"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55"
    d="m 79.653417,167.23317 3.132436,0.37253 c 1.178264,0.46195 2.412495,0.77255 4.017083,0.0824"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55"
    d="m 85.814392,176.15496 0.805311,-0.65519 c -0.222994,-1.87325 0.186028,-2.1542 0.808964,-1.89627"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55"
    d="m 86.799338,168.92944 1.687702,-0.14205 c 0.748556,0.46918 1.497622,0.74832 2.246733,1.0275"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-55"
    d="m 80.397345,160.00703 c 3.116221,-6.12094 8.215197,-1.25459 13.168696,2.80571 1.013692,1.10694 2.300708,1.8024 2.720833,3.80291 1.526838,3.52748 -2.2594,5.53639 -4.040257,7.94844 -1.780786,2.41201 -3.619481,3.61486 -6.837142,2.68508 -7.524533,-2.52035 -7.812053,-5.82518 -6.641983,-9.28801 -2.376235,-5.12525 -0.766686,-5.39501 -0.140907,-6.86318 0.590078,-0.29593 1.179647,-0.43567 1.77076,-1.09095 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-61 child-tooth-61-parent "
    d="m 165.33459,113.36434 c -0.0372,0.31137 -2.03838,3.51787 -2.54292,4.96628 -0.68223,1.56368 -1.76853,2.97728 -4.66113,3.72008 -2.36193,-0.26357 -4.75744,-0.31992 -6.74561,-2.88858 l -4.88802,-6.73607 -0.89133,-2.14289 c -0.64363,-1.5472 0.75022,-2.96996 2.05514,-3.5798 l 3.53829,-0.85744 c 2.06661,-0.12041 3.43827,-0.67075 7.47624,0.42907 l 3.7888,0.75068 c 1.37834,0.55226 3.21891,0.33611 3.25466,3.12029 -0.0725,1.06424 0.38634,2.04671 -0.38412,3.21838 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-61"
    d="m 161.70734,117.48414 0.86624,-6.57099 c -0.46982,0 0.52869,-1.32655 -1.23492,-1.91863 -6.4404,-2.27897 -10.16234,-0.60846 -11.21278,-0.10742 -1.05044,0.50104 -1.96238,1.87133 -1.76083,3.311 0.20149,1.43959 0.64252,1.78533 0.94154,2.50021"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-62 child-tooth-62-parent "
    d="m 180.36094,116.24854 c -7.57478,-5.32687 -6.02296,-3.77204 -9.26492,-4.95626 -1.13012,-0.43265 -2.06815,-0.49734 -2.54498,0.32182 l -1.75787,1.8929 c -1.19456,1.60823 -1.11506,1.94965 -1.2667,2.52087 -0.56273,4.97288 0.0876,4.91047 0.21305,7.027 0.86618,1.80146 1.91227,3.40925 3.68212,4.23767 1.09218,0.82539 2.60468,0.74438 4.2114,0.46019 l 2.64199,-0.6892 c 1.03584,0.16688 1.92835,-0.57323 2.83654,-1.21421 1.91662,-1.44118 2.41191,-3.48678 3.07092,-5.46283 0.13261,-1.27807 -0.20688,-2.41023 -0.98747,-3.4061 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-62"
    d="m 178.22895,125.27493 c 1.01672,-1.69367 2.6443,-3.06234 0.6699,-6.34761 -1.81116,-1.51397 -3.22854,-1.98124 -4.80484,-2.87103 -1.35685,-0.73172 -2.70341,-1.4415 -3.6751,-1.35323 -4.0462,-0.41611 -2.38035,0.61938 -3.47359,0.95373"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-62"
    d="m 169.74074,117.64185 c -0.34636,1.94088 -0.66909,3.9321 -1.6535,4.51489"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-62"
    d="m 172.06141,125.71178 c -0.1433,-1.6529 2.10639,-2.35221 3.95768,-3.21022"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-63 child-tooth-63-parent "
    d="m 190.54157,135.47822 c 3.08307,-0.93464 4.52683,-2.64457 4.85452,-4.88239 0.38088,-2.18804 -0.0399,-4.32208 -1.49376,-6.38661 -1.27151,-3.21521 -2.53322,-3.03715 -3.79603,-3.2531 -1.12648,0.12907 -2.15034,0.0704 -3.14743,-0.0373 -3.01397,-0.12136 -4.13673,1.12063 -4.86302,1.93308 -2.54327,3.09458 -2.26245,4.8977 -2.88098,7.11208 -0.31814,2.44499 -0.1778,4.53561 1.19657,5.6723 4.18185,2.8421 4.8927,1.44955 6.64278,1.32481 3.25904,-1.24723 2.42081,-1.02304 3.48735,-1.48295 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-63"
    d="m 192.45029,133.65605 c -0.14647,-3.02659 -0.42647,-5.89797 -1.98804,-7.2792 -1.54518,-1.47097 -3.53329,-2.53727 -6.25395,-2.93444"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-63"
    d="m 182.93738,126.12663 c -1.844,6.51849 -2.6435,8.05683 2.17539,4.0166"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-63"
    d="m 187.23393,132.41724 c -4.06,4.46477 -1.36697,3.80445 3.00523,1.8697"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-64 child-tooth-64-parent "
    d="m 204.13326,136.7995 c -5.64889,-4.45518 -9.81343,-1.88188 -13.9782,0.69034 -1.12468,1.2682 -2.22493,2.53633 -3.76533,3.80544 -4.71703,4.11933 -3.82979,6.91485 -1.09811,9.27481 1.70252,1.71635 3.33311,3.40022 4.19165,4.73561 0.98398,0.92063 1.88868,1.6269 2.65707,1.96491 3.63626,1.48373 5.02457,-0.58557 7.06579,-1.62299 7.33365,-3.51232 9.95994,-8.405 8.07287,-14.62107 -0.20794,-2.39649 -1.44836,-3.58014 -3.14574,-4.22705 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-64"
    d="m 203.26502,142.20428 c 1.18656,-4.99812 -1.67662,-4.44497 -3.23019,-5.68702 -2.69899,-0.5897 -4.95984,-2.02568 -9.47825,0.89864 -3.36522,4.25514 -2.35912,6.64391 -2.78434,9.64384 -0.20775,2.86427 0.73856,4.60509 2.02397,5.32484 0.31987,1.54509 0.24434,3.02366 3.06576,4.37328 2.40104,1.94542 4.08857,0.27209 5.86251,-0.96306 2.67116,-0.65241 3.1002,-2.24718 3.04032,-4.46079 5.43593,-2.14815 2.39629,-6.20491 1.50022,-9.12973 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-64"
    d="m 192.72633,137.55673 c 0.55271,0.58956 1.29035,0.67045 1.45294,2.33284 -0.24986,1.70417 0.78021,3.40523 1.78232,5.1064 1.03631,0.91641 0.69991,3.40036 0.97909,5.18136 -1.01518,0.8792 -2.03288,0.8891 -3.05081,0.81035 l -1.92393,1.53778"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-64"
    d="m 202.22248,142.86383 c -0.68877,0.67834 -1.20311,1.46175 -3.12953,1.3946 -1.09842,-0.12773 -2.19665,-0.19215 -3.2929,0.4459"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-64"
    d="m 188.95105,143.47675 c 2.10502,0.32885 5.21636,0.1975 5.78846,1.22738"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-64"
    d="m 201.0406,150.97069 c -2.14361,0.88152 -3.05166,-0.0435 -4.17982,-0.64718"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-64"
    d="m 199.28078,153.6762 -0.97192,-2.69903"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65 child-tooth-65-parent "
    d="m 211.85238,167.75167 c 1.14994,-1.43192 0.99957,-3.51626 0.22396,-5.91425 -2.95947,-6.15977 -8.27628,-7.48737 -16.55919,-2.73499 -2.59808,0.953 -4.65771,2.78547 -6.01265,4.6139 -1.33281,1.79862 -2.57033,2.66438 -1.91149,5.84527 1.52117,5.35818 4.14865,9.56133 11.59503,8.73358 2.95865,-0.32704 5.57148,0.37694 9.31355,-2.28548 1.44863,-2.1787 4.23997,-2.03361 3.35079,-8.25803 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65"
    d="m 208.45493,159.80175 c -3.11624,-6.12096 -8.2152,-1.2546 -13.16868,2.80569 -1.01372,1.10697 -2.30072,1.80241 -2.72087,3.80293 -1.52681,3.52748 2.25942,5.53639 4.04026,7.94843 1.78079,2.41202 3.61949,3.61488 6.83715,2.68508 7.52454,-2.52034 7.81204,-5.82517 6.64197,-9.28799 2.37625,-5.12526 0.76671,-5.39505 0.14093,-6.86318 -0.59009,-0.29594 -1.17966,-0.4357 -1.77076,-1.09096 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65"
    d="m 196.49161,162.67757 c 0.75678,0.37371 1.30243,0.36634 1.84901,0.36064 1.45974,-0.19688 2.07321,0.50821 2.25452,1.67384 0.0193,0.73298 -0.19603,1.53763 1.04996,1.89575 1.07361,0.88974 0.53801,1.29628 0.40698,1.82428 0.029,0.82847 0.0575,1.38446 0.0855,1.82503 0.0245,2.97384 -0.87181,2.91007 -1.43487,3.94591 -0.33924,0.33582 -0.75858,0.64754 -0.47872,1.16929 l -0.64124,0.51261"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65"
    d="m 201.46298,159.16131 c -0.30665,0.60913 -0.60478,1.21826 -1.11959,1.8279 -0.57171,0.38872 -0.81331,1.22491 -0.95787,2.19252"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65"
    d="m 207.17105,160.3889 c -1.08141,0.042 -2.16488,0.082 -2.40784,0.88183 -1.37004,0.92112 -2.74422,1.84502 -4.25013,2.8575"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65"
    d="m 209.19886,167.02791 -3.13244,0.37247 c -1.17827,0.46198 -2.4125,0.77258 -4.01709,0.0826"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65"
    d="m 203.03787,175.94966 -0.8053,-0.65515 c 0.22299,-1.87327 -0.18603,-2.15422 -0.8089,-1.89629"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-65"
    d="m 202.05294,168.72414 -1.68772,-0.14203 c -0.74855,0.46918 -1.4976,0.74829 -2.24671,1.0275"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-71 child-tooth-71-parent "
    d="m 157.04765,249.61878 c 0.79665,2.13914 1.36106,4.50647 2.81447,5.99983 0.65206,0.79867 1.21341,1.82059 0.48859,6.00733 -0.7523,1.74435 -2.04036,2.76486 -4.06427,2.79132 -1.63238,-0.37512 -3.03759,-0.9098 -5.6045,-0.62822 -1.2359,-0.36501 -2.36238,0.12449 -3.86373,-2.31361 -0.4959,-1.15152 -1.26819,-1.88482 -0.0122,-5.68577 l 3.84535,-6.27963 c 1.04325,-0.94977 1.15734,-2.41218 3.58892,-2.59601 1.9014,0.0711 2.2579,1.4708 2.80736,2.70476 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-71"
    d="m 159.55657,261.04695 c -1.04939,0.21735 -2.04297,0.63378 -3.22401,0.20415 l -1.90257,0.19999 c -1.33905,0.24223 -2.49029,0.0224 -3.70034,-0.0525 -1.83437,-0.11727 -3.72418,-0.20258 -3.22808,-1.66956"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-72 child-tooth-72-parent "
    d="m 163.16832,244.43026 c 1.00455,-3.42905 3.45759,-3.46833 5.12359,-1.82552 l 2.38653,3.6752 2.80868,3.28623 c 0.40837,1.53809 1.50705,1.44328 0.22638,6.97732 -0.77015,2.99339 -2.3191,4.33093 -4.21829,4.9239 -2.43561,0.85504 -4.52096,0.30545 -6.39837,-1.07789 -1.27985,-1.61164 -3.30565,-2.37216 -2.76176,-6.06459 0.54285,-1.66058 0.56865,-2.04881 1.7332,-5.23909 0.36668,-1.54502 0.73507,-2.30656 1.10004,-4.65556 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-72"
    d="m 168.66926,246.0279 c 1.35346,2.40788 5.10664,4.80797 0.86127,7.23378"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-72"
    d="m 165.50305,248.55788 -0.93957,5.55965 c -0.61099,2.67627 -1.23002,1.5743 -1.85015,0.007"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-72"
    d="m 173.48945,250.66454 c -0.0966,1.96247 0.0533,4.11741 -1.94599,4.59364 -1.6999,0.71613 -3.83243,1.43369 -4.48853,2.14654 -2.81374,1.89937 -3.07618,0.905 -4.22687,0.91812"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-73 child-tooth-73-parent "
    d="m 182.01732,234.09667 c -3.53712,-0.60748 -6.63299,-0.62741 -7.23613,2.67223 l -1.7834,6.46689 c -1.28208,4.92898 0.0639,3.93133 0.27605,5.49111 l 2.6512,3.80356 c 1.01314,1.25362 2.10478,2.43968 4.49845,2.50549 3.50956,-0.15865 7.06059,-0.22655 7.8096,-6.42156 l 0.30162,-7.23747 c -0.72778,-3.45824 -1.52434,-6.86737 -6.51739,-7.28025 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-73"
    d="m 186.36428,239.89779 c -1.04021,4.02741 -1.69152,8.64582 -4.89587,9.38439 -2.49196,1.38896 -5.03985,3.62604 -7.13819,-0.94639"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-73"
    d="m 175.48844,246.13529 c 1.26676,0.92061 2.58382,2.00094 2.74747,-0.59023 0.2297,-2.7008 -0.72539,-3.94577 -1.12268,-5.87607"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-73"
    d="m 184.14407,239.84031 c -0.29637,-1.61148 -1.2667,-2.84004 -3.39039,-3.41355"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-73"
    d="m 183.04175,243.46208 c -0.72067,0.84026 -1.44452,0.15635 -2.17256,-2.4483 -0.37862,-1.98037 -1.04975,-2.51453 -1.69824,-3.16051"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-74 child-tooth-74-parent "
    d="m 199.27678,222.0237 c 2.0578,3.46842 2.55167,5.9565 2.343,8.00435 -0.61556,3.56301 -0.93416,7.38683 -3.99852,8.79991 -4.8904,1.99162 -5.94305,0.33877 -7.92997,-0.42679 -2.27359,-1.35121 -4.06144,-3.04371 -5.34979,-5.08727 -0.99077,-0.83673 -0.91674,-1.67692 -0.95687,-2.51679 0.15307,-1.56023 -0.12159,-2.61348 -0.21976,-3.876 -0.14961,-2.44868 0.71515,-4.15778 1.20263,-6.14195 1.44194,-1.43504 2.49838,-3.40576 4.85551,-3.56915 l 4.06995,-0.14225 c 2.50703,-0.16061 4.21712,2.4977 5.98382,4.95594 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-74"
    d="m 198.22107,222.73781 c -0.72711,1.24986 -1.62958,2.72126 -2.10688,3.4958 -0.2565,0.55303 -0.0399,0.94263 -0.10182,1.8094 0.30571,2.24991 -0.26507,2.10421 -0.78739,2.58703 -0.53296,0.50516 -1.07998,0.97636 -1.47621,1.81385 -0.45461,1.68005 -1.10042,1.30649 -1.6882,1.55602 l -2.43046,0.5247 c -1.9282,0.70948 -2.23142,-0.37751 -3.27822,-0.4418"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-74"
    d="m 186.92525,230.33355 c -0.12671,0.85785 0.32871,1.81549 -0.68173,2.52203"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-74"
    d="m 192.16049,231.28593 -4.50722,-6.57596 c -0.94712,-1.39894 -1.67158,-1.73757 -1.74217,1.03938"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-74"
    d="m 192.88788,225.40399 c -0.44149,-1.65698 -1.68357,-3.31141 -3.81653,-4.9629 0.82609,0.35232 1.65002,0.7126 2.74887,0.0562 1.30254,-0.78036 1.92324,0.11728 2.69719,0.63745"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-75 child-tooth-75-parent "
    d="m 198.383,199.60633 c -5.18932,3.15259 -5.11371,4.8478 -5.27049,7.25337 0.39286,2.88118 0.28796,3.95303 0.2758,5.36194 -0.0701,2.96167 1.43908,3.74017 2.39004,5.29043 3.04952,3.72954 4.67406,2.52421 6.82572,3.14421 1.97858,-0.3725 3.18428,-0.74255 4.33214,-1.11233 3.22667,-0.95734 2.94466,-2.4393 4.168,-3.69621 1.5685,-2.57261 1.70637,-5.85991 0.87803,-9.62991 -0.67928,-2.24278 -2.05789,-3.92006 -3.50063,-5.54542 l -3.54634,-2.18544 c -2.72247,-1.3759 -5.84154,0.51296 -6.55227,1.11936 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-75"
    d="m 203.24568,199.39694 c 1.13008,0.27784 2.49882,0.4835 2.96778,3.54421 -0.009,1.33107 0.33994,2.36783 0.74722,3.3574 0.49188,3.19157 -0.79302,5.3044 -1.93972,7.50114 -0.89034,1.83662 -1.99222,2.02025 -3.06069,2.46501 -1.16521,0.75278 -2.03941,0.24806 -2.64598,-1.41302 -0.37103,-0.94802 -0.44781,-2.3481 -1.32692,-2.5156 -0.39707,-0.85614 -0.44969,-1.57313 -0.42774,-2.26002 0.50001,-1.14724 0.0947,-2.73378 -0.0618,-4.19954 2.05163,-0.12607 2.55584,-2.13357 2.79197,-4.46713 0.9546,-0.4419 1.87062,-0.59659 2.95589,-2.01245 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-75"
    d="m 200.79145,213.49015 c -1.21692,-1.34722 -1.19479,-3.70985 -1.17597,-6.06975 0.74778,-3.95725 2.104,-5.84897 4.27103,-4.98877"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-75"
    d="m 198.46021,210.97776 c 0.0955,-2.03006 0.60134,-2.9397 1.2613,-3.42845"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-75"
    d="m 201.5053,201.27629 -0.1547,1.80957"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-81 child-tooth-81-parent "
    d="m 134.25341,249.5834 c -0.79665,2.13912 -1.36102,4.50647 -2.81448,5.99982 -0.65207,0.79867 -1.21341,1.8206 -0.48858,6.00735 0.7523,1.74435 2.04033,2.76485 4.06425,2.79131 1.63241,-0.37512 3.03762,-0.9098 5.6045,-0.62821 1.23591,-0.36501 2.3624,0.12448 3.86374,-2.31361 0.49589,-1.15152 1.26818,-1.88482 0.0122,-5.68579 l -3.84536,-6.27961 c -1.04325,-0.94977 -1.15735,-2.41216 -3.58891,-2.59603 -1.90139,0.0711 -2.25789,1.47082 -2.80736,2.70477 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-81"
    d="m 131.74447,261.01157 c 1.04939,0.21735 2.04297,0.63377 3.22403,0.20418 l 1.90256,0.19995 c 1.33905,0.24223 2.49029,0.0225 3.70035,-0.0525 1.83436,-0.11727 3.72417,-0.20253 3.22806,-1.66958"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-82"
    d="m 122.63179,245.99259 c -1.35347,2.40782 -5.10665,4.80799 -0.86126,7.23371"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-82"
    d="m 125.79801,248.5225 0.93957,5.55963 c 0.61096,2.67631 1.23002,1.57433 1.85014,0.007"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-82"
    d="m 117.81161,250.62914 c 0.0967,1.96249 -0.0533,4.11742 1.94598,4.59367 1.69991,0.71613 3.83248,1.43369 4.48853,2.14652 2.81374,1.89936 3.07617,0.905 4.22686,0.91815"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-82 child-tooth-82-parent "
    d="m 128.13274,244.39491 c -1.00456,-3.42909 -3.45758,-3.46836 -5.12358,-1.82555 l -2.38655,3.67521 -2.80866,3.28622 c -0.40838,1.5381 -1.50708,1.44328 -0.22638,6.97731 0.77014,2.99338 2.3191,4.33094 4.21828,4.92394 2.43561,0.85503 4.52094,0.30542 6.39837,-1.07794 1.27981,-1.6116 3.30567,-2.37213 2.76177,-6.06456 -0.54286,-1.66058 -0.56866,-2.04881 -1.73323,-5.23911 -0.36667,-1.545 -0.73509,-2.30654 -1.10002,-4.65552 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-83 child-tooth-83-parent "
    d="m 109.28374,234.06128 c 3.53711,-0.60746 6.63297,-0.62739 7.23611,2.67224 l 1.78342,6.46692 c 1.28209,4.92896 -0.0641,3.93131 -0.27605,5.49109 l -2.65119,3.80359 c -1.01316,1.25357 -2.10481,2.43963 -4.49847,2.50547 -3.50957,-0.15869 -7.06062,-0.22658 -7.80961,-6.42158 l -0.30161,-7.23747 c 0.72778,-3.45821 1.52436,-6.86736 6.5174,-7.28026 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-83"
    d="m 104.93674,239.86242 c 1.04025,4.02739 1.69157,8.64584 4.89591,9.38438 2.49196,1.38896 5.03983,3.62606 7.13818,-0.94637"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-83"
    d="m 115.8126,246.09993 c -1.26676,0.92057 -2.58374,2.00091 -2.74746,-0.59023 -0.22971,-2.70079 0.7254,-3.94578 1.12267,-5.8761"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-83"
    d="m 107.157,239.80493 c 0.29635,-1.61148 1.2667,-2.84003 3.39037,-3.41354"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-83"
    d="m 108.25931,243.42671 c 0.72066,0.84026 1.44451,0.15634 2.17253,-2.44829 0.37864,-1.98038 1.04977,-2.51453 1.69833,-3.16054"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-84 child-tooth-84-parent "
    d="m 92.024272,221.98833 c -2.057804,3.46842 -2.551663,5.95651 -2.342996,8.00434 0.615559,3.563 0.934165,7.38685 3.998519,8.79993 4.890397,1.99163 5.943042,0.33877 7.929975,-0.42683 2.27359,-1.35118 4.06144,-3.04368 5.34979,-5.08725 0.99076,-0.83673 0.91674,-1.67692 0.95686,-2.51681 -0.15305,-1.56021 0.12159,-2.61346 0.21976,-3.87599 0.14961,-2.44867 -0.71508,-4.15779 -1.20263,-6.14194 -1.44195,-1.43501 -2.49839,-3.40574 -4.85551,-3.56913 l -4.069961,-0.14226 c -2.507029,-0.16062 -4.217119,2.49768 -5.983807,4.95594 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-84"
    d="m 93.07998,222.70245 c 0.727118,1.24986 1.629584,2.72124 2.106884,3.49579 0.256495,0.55302 0.03991,0.94261 0.101852,1.8094 -0.305714,2.2499 0.265072,2.1042 0.787399,2.58703 0.532961,0.50515 1.079969,0.97636 1.476211,1.81384 0.454606,1.68005 1.100403,1.3065 1.688185,1.55601 l 2.430459,0.52472 c 1.9282,0.70948 2.23141,-0.37752 3.27824,-0.44182"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-84"
    d="m 104.37579,230.29817 c 0.12671,0.85787 -0.32871,1.81556 0.68174,2.52204"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-84"
    d="m 99.140557,231.25055 4.507213,-6.57597 c 0.94712,-1.39892 1.6716,-1.73753 1.74217,1.03941"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-84"
    d="m 98.41316,225.36863 c 0.441479,-1.657 1.68359,-3.31142 3.81654,-4.96292 -0.82608,0.35233 -1.65,0.7126 -2.748824,0.0562 -1.302585,-0.78037 -1.923291,0.11716 -2.697247,0.63745"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-85 child-tooth-85-parent "
    d="m 92.918039,199.57096 c 5.189319,3.15261 5.113713,4.8478 5.2705,7.25336 -0.392855,2.88121 -0.28794,3.95306 -0.275792,5.36194 0.07019,2.96176 -1.439077,3.74019 -2.390045,5.29043 -3.049451,3.72956 -4.674063,2.52422 -6.825701,3.14422 -1.978616,-0.37253 -3.184305,-0.74254 -4.332149,-1.11234 -3.226667,-0.95732 -2.944671,-2.43932 -4.168028,-3.6962 -1.568502,-2.57262 -1.706341,-5.85989 -0.878005,-9.6299 0.679285,-2.24279 2.057877,-3.92008 3.500623,-5.54541 l 3.546321,-2.18545 c 2.722498,-1.37593 5.841551,0.51294 6.552276,1.11935 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-85"
    d="m 88.055374,199.36157 c -1.130098,0.27782 -2.49883,0.48349 -2.967784,3.54418 0.0098,1.33111 -0.339953,2.36787 -0.747215,3.35745 -0.491888,3.19153 0.793024,5.30436 1.939728,7.50111 0.890325,1.83663 1.992204,2.02026 3.060671,2.46501 1.165218,0.75278 2.039434,0.24806 2.646041,-1.41303 0.370967,-0.94803 0.447736,-2.34807 1.326844,-2.51559 0.3971,-0.85612 0.449715,-1.57309 0.427757,-2.26001 -0.499923,-1.14726 -0.09473,-2.73378 0.06179,-4.19954 -2.051573,-0.12607 -2.555854,-2.13359 -2.791993,-4.46713 -0.954582,-0.4419 -1.870614,-0.59659 -2.955874,-2.01245 z"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-85"
    d="m 90.509607,213.45478 c 1.216915,-1.34722 1.194773,-3.70985 1.175948,-6.06976 -0.747754,-3.95724 -2.103987,-5.84896 -4.271033,-4.98876"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-85"
    d="m 92.84084,210.94241 c -0.09552,-2.03008 -0.601338,-2.93974 -1.261303,-3.42847"
    style="fill:none;stroke:#000000;stroke-width:1;stroke-linecap:butt;stroke-linejoin:miter;stroke-miterlimit:4;stroke-opacity:1;stroke-dasharray:none"/>
  <path
    class="child-tooth-85"
    d="m 89.795748,201.24091 0.154722,1.80957"
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

            <!-- Dental Update Tab Content -->
</div>

        <!-- Right side: Dental History -->
        <div class="dental-examination-form">
        <div class="dental-history">
            <h2>Dental History</h2>
            @if($dentalRecord)
    <a href="{{ route('student.dentalRecord.pdf', $dentalRecord->id_number) }}" class="btn btn-primary">Download Dental Record</a>
@endif
    </a>

          <!-- Patient Information -->
<table class="history-table">
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
        <tr>
            <td><strong>Dentist Name:</strong></td>
            <td>Dr. Sarah Uy-Gan</td>
        </tr>
    </tbody>
</table>

<!-- Previous Examinations -->
<table class="history-table">
    <thead>
        <tr>
            <th colspan="3">Previous Examinations</th>
        </tr>
    </thead>
    <tbody>
    @if($lastExamination && is_countable($lastExamination) && count($lastExamination) > 0)
        @foreach($lastExamination as $examination)
        <tr>
            <td><strong>Date:</strong></td>
            <td>{{ $examination->date_of_examination }}</td>
            <td>{{ $examination->findings ?? 'No findings available' }}</td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3">No previous examinations found</td>
        </tr>
    @endif
</tbody>


<!-- Treatments Performed -->
<table class="history-table">
    <thead>
        <tr>
            <th colspan="4">Treatments Performed</th>
        </tr>
    </thead>
 
</table>

<!-- Medications Prescribed -->
<table class="history-table">
    <thead>
        <tr>
            <th colspan="4">Medications Prescribed</th>
        </tr>
    </thead>
   
</table>

<!-- Next Scheduled Appointment -->
<table class="history-table">
    <thead>
        <tr>
            <th colspan="2">Next Scheduled Appointment with Dr. Sarah Uy-Gan</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<!-- Additional Notes -->
<table class="history-table">
    <thead>
        <tr>
            <th colspan="2">Additional Notes</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="2">{{ $patient->additional_notes ?? 'No additional notes' }}</td>
        </tr>
    </tbody>
</table>


            <!-- Dental Update Button -->
           
        </div>
    </div>
</div>

    <!-- Preview Modal -->
<!-- Modal to update tooth details, status, and upload images -->
<!-- Modal to update tooth details, status, and upload images -->
<div id="previewModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Tooth Details</h2>
        <form id="tooth-details-form">
            @csrf
            <div class="form-group">
                <label for="modal-tooth">Tooth:</label>
                <input type="hidden" id="dental-record-id" name="dental_record_id" value="{{ $dentalRecord->id ?? '' }}">
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

            <!-- Image Upload Section in the Modal -->
            <div class="form-group">
                <label for="modal-upload-images">Upload Dental Pictures:</label>
                <input type="file" id="modal-upload-images" name="update_images[]" class="form-control" accept="image/*" multiple>
            </div>
            <div id="image-preview-container" class="image-preview-container"></div>

            <button type="button" id="save-tooth-details" class="save-button">Save</button>
        </form>
    </div>
</div>


    <script>
var storeToothUrl = "{{ route('student.teeth.store') }}";
var storeDentalRecordUrl = "{{ route('student.dental-record.store') }}";
var getToothStatusUrl = "{{ route('student.get-tooth-status') }}"; // Pass the route to JS
let teethStatuses = @json($teeth->pluck('status', 'tooth_number'));
console.log('Teeth Statuses:', teethStatuses); 

</script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>
    <script src="{{ asset('js/dental.js') }}"></script>
  
</x-app-layout>
