<x-app-layout>
    <style>
        body {
            background: url('{{ asset('images/bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            animation: fadeInBackground 1s ease-in-out;
        }

        .form-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
            width: 100%;
            box-sizing: border-box;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            width: 100%;
            font-size: 18px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: calc(50% - 10px);
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-group textarea {
            width: 100%;
            padding-left: 15px;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 15px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            font-size: 18px;
        }

        .form-group button:hover {
            background-color: #00b8e6;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .input-wrapper {
            position: relative;
            width: calc(50% - 10px);
            margin-bottom: 20px;
        }

        .input-wrapper::before {
            content: attr(data-icon);
            position: absolute;
            left: 10px;
            top: 60%;
            transform: translateY(-50%);
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: black;
            z-index: 2;
            font-size: 20px;
        }

        .input-wrapper input,
        .input-wrapper select {
            width: 100%;
            padding-left: 45px;
        }

        .textarea-wrapper {
            position: relative;
            width: 100%;
        }

        .textarea-wrapper label {
            display: flex;
            align-items: center;
        }

        .textarea-wrapper label::before {
            content: "\f0f9";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: black;
            margin-right: 10px;
            font-size: 20px;
        }

        .textarea-wrapper textarea {
            width: 100%;
            padding-left: 15px;
        }

        .complaint-status {
            display: none;
            margin-top: 20px;
            text-align: center;
        }

        .complaint-status.active {
            display: block;
        }

        .status {
            display: block;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .status-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .status-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #mark-completed {
            background-color: #28a745;
            color: white;
        }

        #mark-not-completed {
            background-color: #dc3545;
            color: white;
        }
    </style>

    <div class="form-container" id="complaint-form-container">
        <h2>{{ ucfirst($role) }} Health Complaint</h2>
        <form id="complaint-form">
            @csrf
            <input type="hidden" name="role" value="{{ strtolower(Auth::user()->role) }}">
            <div class="form-group">
                <div class="input-wrapper" data-icon="&#xf007;">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="input-wrapper" data-icon="&#xf254;">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper" data-icon="&#xf133;">
                    <label for="birthdate">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                </div>
                <div class="input-wrapper" data-icon="&#xf095;">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" id="contact_number" name="contact_number" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper" data-icon="&#xf0f1;">
                    <label for="health_history">Health History</label>
                    <input type="text" id="health_history" name="health_history" required>
                </div>
                <div class="input-wrapper" data-icon="&#xf0f0;">
                    <label for="pain_assessment">Pain Assessment (1 to 10)</label>
                    <select id="pain_assessment" name="pain_assessment" required>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="textarea-wrapper">
                    <label for="sickness_description">Description of Sickness</label>
                    <textarea id="sickness_description" name="sickness_description" rows="4" required></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="input-wrapper" data-icon="&#xf19d;">
                    <label for="student_type">Student Type</label>
                    <select id="student_type" name="student_type" required onchange="toggleStudentFields()">
                        <option value="">Select Student Type</option>
                        <option value="TED">TED</option>
                        <option value="BED">BED</option>
                        <option value="SHS">SHS</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="ted-fields" style="display: none;">
                <div class="input-wrapper" data-icon="&#xf19d;">
                    <label for="program">Program (for TED)</label>
                    <select id="program" name="program">
                        <option value="">Select Program</option>
                        <option value="BSN">BSN</option>
                        <option value="BSHM">BSHM</option>
                        <option value="BSTM">BSTM</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BEED">BEED</option>
                        <option value="BLIS">BLIS</option>
                        <option value="BSBA">BSBA</option>
                    </select>
                </div>
                <div class="input-wrapper" data-icon="&#xf549;">
                    <label for="year">Year</label>
                    <select id="year" name="year">
                        <option value="">Select Year</option>
                        @for ($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}">{{ $i }} Year</option>
                        @endfor
                    </select>
                </div>
                <div class="input-wrapper" data-icon="&#xf2bb;">
                    <label for="ted_section">Section</label>
                    <select id="ted_section" name="ted_section">
                        <option value="">Select Section</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                    </select>
                </div>
            </div>
            <div class="form-group" id="bed-fields" style="display: none;">
                <div class="input-wrapper" data-icon="&#xf549;">
                    <label for="bed_grade">Grade (for BED)</label>
                    <select id="bed_grade" name="bed_grade">
                        <option value="">Select Grade</option>
                        <option value="Kinder 1">Kinder 1</option>
                        <option value="Kinder 2">Kinder 2</option>
                        @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">Grade {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="input-wrapper" data-icon="&#xf2bb;">
                    <label for="bed_section">Section</label>
                    <input type="text" id="bed_section" name="bed_section">
                </div>
            </div>
            <div class="form-group" id="shs-fields" style="display: none;">
                <div class="input-wrapper" data-icon="&#xf549;">
                    <label for="shs_grade">Grade (for SHS)</label>
                    <select id="shs_grade" name="shs_grade">
                        <option value="">Select Grade</option>
                        <option value="11">Grade 11</option>
                        <option value="12">Grade 12</option>
                    </select>
                </div>
                <div class="input-wrapper" data-icon="&#xf549;">
                    <label for="strand">Strand (for SHS)</label>
                    <select id="strand" name="strand">
                        <option value="">Select Strand</option>
                        <option value="STEM">STEM</option>
                        <option value="HUMSS">HUMSS</option>
                        <option value="ABM">ABM</option>
                    </select>
                </div>
                <div class="input-wrapper" data-icon="&#xf2bb;">
                    <label for="shs_section">Section</label>
                    <input type="text" id="shs_section" name="shs_section">
                </div>
            </div>
            <div class="form-group">
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>

    <div class="complaint-status" id="complaint-status-container">
        <span class="status">Status: <span id="complaint-status">Not Yet Done</span></span>
        <div class="status-buttons">
            <button id="mark-completed" onclick="updateStatus('Completed')">Completed</button>
            <button id="mark-not-completed" onclick="updateStatus('Not yet done')">Not Yet Done</button>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleStudentFields() {
            var studentType = document.getElementById('student_type').value;
            document.getElementById('ted-fields').style.display = studentType === 'TED' ? 'block' : 'none';
            document.getElementById('bed-fields').style.display = studentType === 'BED' ? 'block' : 'none';
            document.getElementById('shs-fields').style.display = studentType === 'SHS' ? 'block' : 'none';

            document.querySelectorAll('#ted-fields input, #ted-fields select').forEach(el => {
                el.required = studentType === 'TED';
            });
            document.querySelectorAll('#bed-fields input, #bed-fields select').forEach(el => {
                el.required = studentType === 'BED';
            });
            document.querySelectorAll('#shs-fields input, #shs-fields select').forEach(el => {
                el.required = studentType === 'SHS';
            });
        }

        document.getElementById('complaint-form').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route("parent.complaint.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(JSON.stringify(errorData));
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                }).then(() => {
                    document.getElementById('complaint-form-container').style.display = 'none';
                    document.getElementById('complaint-status-container').classList.add('active');
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again. ' + error.message
                });
            });
        });

        function updateStatus(status) {
            fetch('{{ route("parent.complaint.update-status", ["id" => 1]) }}', { // Replace 1 with the actual complaint ID
                method: 'POST',
                body: JSON.stringify({ status: status }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('complaint-status').innerText = status;
                    if (status === 'Completed') {
                        document.getElementById('complaint-form').reset();
                        document.getElementById('complaint-form-container').style.display = 'block';
                        document.getElementById('complaint-status-container').classList.remove('active');
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not update status. Please try again.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.'
                });
            });
        }
    </script>
</x-app-layout>