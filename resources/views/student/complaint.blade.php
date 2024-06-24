<x-app-layout>
    <style>
        .form-container {
            margin-top: 20px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.5s ease-in-out;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group button {
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #00b8e6;
        }

        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            animation: fadeInUp 0.5s ease-in-out;
        }

        .appointment-table th,
        .appointment-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .appointment-table th {
            background-color: #00d1ff;
            color: white;
        }

        .appointment-table td input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .add-complaint-button {
            display: inline-block;
            background-color: #00d1ff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .add-complaint-button:hover {
            background-color: #00b8e6;
            transform: scale(1.05);
        }

        .add-complaint-button:active {
            transform: scale(0.95);
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
    </style>

    <div class="form-container">
        <h2>{{ ucfirst($role) }} Health Complaint</h2>
        <form method="POST" action="{{ route($role . '.complaint.add') }}">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="year_and_section">Year and Section</label>
                <input type="text" id="year_and_section" name="year_and_section" required>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" required>
            </div>
            <div class="form-group">
                <label for="health_history">Health History</label>
                <input type="text" id="health_history" name="health_history" required>
            </div>

            <div class="complaints-section">
                
                <button type="button" class="add-complaint-button" onclick="addComplaintRow()">Add Complaint</button>
            </div>

            
        </form>
    </div>

    <script>
        let complaintIndex = 1;
        function addComplaintRow() {
            const tableBody = document.getElementById('complaints-table-body');
            const newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td><input type="datetime-local" name="complaints[${complaintIndex}][datetime]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][complaint]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][management]" required></td>
                <td><input type="text" name="complaints[${complaintIndex}][remarks]" required></td>
            `;

            tableBody.appendChild(newRow);
            complaintIndex++;
        }

        document.getElementById('notification-icon').addEventListener('click', function() {
            var dropdown = document.getElementById('notification-dropdown');
            dropdown.classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            var dropdown = document.getElementById('notification-dropdown');
            var icon = document.getElementById('notification-icon');
            if (!dropdown.contains(event.target) && !icon.contains(event.target)) {
                dropdown.classList.remove('active');
                icon.classList.remove('active');
            }
        });
    </script>
</x-app-layout>
