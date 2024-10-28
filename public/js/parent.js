$(document).ready(function () {
    const teethData = {
        '11': 'Upper Right Central Incisor',
        '12': 'Upper Right Lateral Incisor',
        '13': 'Upper Right Canine',
        '14': 'Upper Right First Premolar',
        '15': 'Upper Right Second Premolar',
        '16': 'Upper Right First Molar',
        '17': 'Upper Right Second Molar',
        '18': 'Upper Right Third Molar',
        '21': 'Upper Left Central Incisor',
        '22': 'Upper Left Lateral Incisor',
        '23': 'Upper Left Canine',
        '24': 'Upper Left First Premolar',
        '25': 'Upper Left Second Premolar',
        '26': 'Upper Left First Molar',
        '27': 'Upper Left Second Molar',
        '28': 'Upper Left Third Molar',
        '31': 'Lower Left Central Incisor',
        '32': 'Lower Left Lateral Incisor',
        '33': 'Lower Left Canine',
        '34': 'Lower Left First Premolar',
        '35': 'Lower Left Second Premolar',
        '36': 'Lower Left First Molar',
        '37': 'Lower Left Second Molar',
        '38': 'Lower Left Third Molar',
        '41': 'Lower Right Central Incisor',
        '42': 'Lower Right Lateral Incisor',
        '43': 'Lower Right Canine',
        '44': 'Lower Right First Premolar',
        '45': 'Lower Right Second Premolar',
        '46': 'Lower Right First Molar',
        '47': 'Lower Right Second Molar',
        '48': 'Lower Right Third Molar'
    };

    // Function to determine the fill color based on the tooth status
    function getColorBasedOnStatus(status) {
        if (!status) {
            return 'green'; // Default color if status is missing
        }

        const normalizedStatus = status.toLowerCase();
        switch (normalizedStatus) {
            case 'aching':
                return 'red';
            case 'cavity':
                return 'orange';
            case 'missing':
                return 'gray';
            case 'healthy':
                return 'green';
            default:
                return 'green'; // Default color for unknown statuses
        }
    }

    // Function to apply fill color to a tooth
    function applyToothColor(toothNumber, fillColor) {
        const toothElement = $(`.tooth-${toothNumber}`);
        if (toothElement.length) {
            toothElement.css({
                'fill': fillColor,
                'transition': 'fill 0.5s ease-in-out'
            });
        }
    }

    // Function to populate tooth colors based on status
    function populateTeeth(teeth) {
        const allTeethNumbers = Object.keys(teethData); // List of all tooth numbers

        // First, set all teeth to default green color
        allTeethNumbers.forEach(function (toothNumber) {
            applyToothColor(toothNumber, 'green'); // Default color
        });

        // Now, if we have data for teeth, overwrite colors
        if (Array.isArray(teeth)) {
            teeth.forEach(function (tooth) {
                const toothNumber = tooth.tooth_number;
                const status = tooth.status;

                if (toothNumber === undefined || status === undefined) {
                    console.warn(`Incomplete tooth data:`, tooth);
                    return; // Skip this tooth if data is incomplete
                }

                const fillColor = getColorBasedOnStatus(status);
                applyToothColor(toothNumber, fillColor);
            });
        } else {
            console.warn('Teeth data is undefined or not an array. All teeth will be displayed with default color.');
        }
    }

    // Mapping for boolean values
    const booleanMapping = {
        0: 'No',
        1: 'Yes',
        '0': 'No',
        '1': 'Yes',
        true: 'Yes',
        false: 'No',
    };

    // Function to map boolean values
    function mapBoolean(value) {
        return booleanMapping[value] || 'N/A';
    }

    // Function to populate dental history
    function populateDentalHistory(dentalRecord) {
        if (!dentalRecord) {
            console.error('Dental history data is undefined or null:', dentalRecord);
            return;
        }

        // Populate Patient Information
        const patientInfoBody = $('#patient-info-body');
        patientInfoBody.empty();

        const formattedDOB = dentalRecord.patientInfo && dentalRecord.patientInfo.birthdate ? new Date(dentalRecord.patientInfo.birthdate).toLocaleDateString() : 'N/A';
        const lastVisitDate = dentalRecord.lastExamination && dentalRecord.lastExamination.date_of_examination ? new Date(dentalRecord.lastExamination.date_of_examination).toLocaleDateString() : 'N/A';

        const patientName = dentalRecord.personInfo ? `${dentalRecord.personInfo.first_name} ${dentalRecord.personInfo.last_name}` : 'N/A';

        // **Added Grade and Section Fields**
        const grade = dentalRecord.patientInfo && dentalRecord.patientInfo.grade ? dentalRecord.patientInfo.grade : 'N/A';
        const section = dentalRecord.patientInfo && dentalRecord.patientInfo.section ? dentalRecord.patientInfo.section : 'N/A';

        patientInfoBody.append(`
            <tr>
                <td><strong>Patient Name:</strong></td>
                <td>${patientName}</td>
            </tr>
            <tr>
                <td><strong>Date of Birth:</strong></td>
                <td>${formattedDOB}</td>
            </tr>
            <tr>
                <td><strong>Grade:</strong></td>
                <td>${grade}</td>
            </tr>
            <tr>
                <td><strong>Section:</strong></td>
                <td>${section}</td>
            </tr>
            <tr>
                <td><strong>Last Visit Date:</strong></td>
                <td>${lastVisitDate}</td>
            </tr>
        `);

        // Populate Previous Examinations
        const prevExamBody = $('#dental-examination-history-body');
        prevExamBody.empty();
        if (dentalRecord.previousExaminations && dentalRecord.previousExaminations.length > 0) {
            dentalRecord.previousExaminations.forEach(exam => {
                const formattedDate = exam.date_of_examination ? new Date(exam.date_of_examination).toLocaleDateString() : 'N/A';
                const dentistName = exam.dentist_name || 'N/A';

                // Collect additional findings based on fields with value true
                const additionalFindings = [];
                const examinationFields = {
                    carries_free: 'Carries Free',
                    poor_oral_hygiene: 'Poor Oral Hygiene',
                    gum_infection: 'Gum Infection',
                    restorable_caries: 'Restorable Caries',
                    other_condition: 'Other Condition',
                    personal_attention: 'Personal Attention',
                    oral_prophylaxis: 'Oral Prophylaxis',
                    fluoride_application: 'Fluoride Application',
                    gum_treatment: 'Gum Treatment',
                    ortho_consultation: 'Orthodontic Consultation',
                    sealant_tooth: 'Sealant Tooth(s)',
                    filling_tooth: 'Filling Tooth(s)',
                    extraction_tooth: 'Extraction Tooth(s)',
                    endodontic_tooth: 'Endodontic Tooth(s)',
                    radiograph_tooth: 'Radiograph Tooth(s)',
                    prosthesis_tooth: 'Prosthesis Tooth(s)',
                    medical_clearance: 'Medical Clearance',
                    other_recommendation: 'Other Recommendation'
                };

                Object.keys(examinationFields).forEach(field => {
                    if (Array.isArray(exam[field]) && exam[field].length > 0) {
                        const teethList = exam[field].map(toothNum => {
                            const toothKey = toothNum.toString();
                            const toothName = teethData[toothKey] || 'Unknown Tooth';
                            return `${toothKey}: ${toothName}`;
                        }).join(', ');
                        additionalFindings.push(`${examinationFields[field]}: ${teethList}`);
                    } else if (exam[field] === true || exam[field] === 1) {
                        additionalFindings.push(examinationFields[field]);
                    }
                });

                // Prepare HTML for additional findings
                let findingsHtml = 'N/A'; // Default value
                if (additionalFindings.length > 0) {
                    findingsHtml = '<ul>';
                    additionalFindings.forEach(item => {
                        findingsHtml += `<li>${item}</li>`;
                    });
                    findingsHtml += '</ul>';
                }

                // Updated row without the main findings
                const row = `
                    <tr>
                        <td>${formattedDate}</td>
                        <td>${dentistName}</td>
                        <td>${findingsHtml}</td>
                    </tr>
                `;
                prevExamBody.append(row);
            });
        } else {
            prevExamBody.append('<tr><td colspan="3">No previous examinations found.</td></tr>');
        }

        // Populate Tooth History
        const toothHistoryBody = $('#tooth-history-body');
        toothHistoryBody.empty();
        if (dentalRecord.toothHistory && dentalRecord.toothHistory.length > 0) {
            dentalRecord.toothHistory.forEach(tooth => {
                const toothNumber = tooth.tooth_number || 'N/A';
                const status = tooth.status || 'N/A';
                const notes = tooth.notes || 'N/A';
                const formattedDate = tooth.updated_at ? new Date(tooth.updated_at).toLocaleDateString() : 'N/A';

                // Handle 'dental_pictures' which should be an array
                let dentalPictures = tooth.dental_pictures || [];
                if (typeof dentalPictures === 'string') {
                    try {
                        dentalPictures = JSON.parse(dentalPictures);
                    } catch (e) {
                        console.error('Error parsing dental_pictures:', e);
                        dentalPictures = [];
                    }
                }

                let picturesHtml = 'N/A';
                if (Array.isArray(dentalPictures) && dentalPictures.length > 0) {
                    picturesHtml = dentalPictures.map(pic => `<img src="/storage/${pic}" alt="Dental Picture" style="max-width: 100px; margin-right: 5px; cursor: pointer;" class="dental-picture-preview">`).join('');
                }

                const row = `
                    <tr>
                        <td>Tooth ${toothNumber}</td>
                        <td>${status}</td>
                        <td>${notes}</td>
                        <td>${picturesHtml}</td>
                        <td>${formattedDate}</td>
                    </tr>
                `;
                toothHistoryBody.append(row);
            });

            // **Initialize DataTables for Tooth History Table Here**
            $('#tooth-history-table').DataTable({
                responsive: true,
                searching: true,
                paging: true,
            });
        } else {
            toothHistoryBody.append('<tr><td colspan="5">No tooth history found.</td></tr>');
        }
    }

    // Function to handle image preview using SweetAlert
    $(document).on('click', '.dental-picture-preview', function () {
        const src = $(this).attr('src');
        Swal.fire({
            title: 'Image Preview',
            imageUrl: src,
            imageAlt: 'Dental Picture',
            showConfirmButton: false,
            showCloseButton: true,
        });
    });

    // Now, on document ready, populate the dental record
    // Assuming that dentalRecordData and teeth are provided from the Blade view
    if (typeof dentalRecordData !== 'undefined' && dentalRecordData) {
        populateDentalHistory(dentalRecordData);
    } else {
        console.error('dentalRecordData is undefined or null.');
    }

    if (typeof teeth !== 'undefined' && teeth) {
        populateTeeth(teeth);
    } else {
        console.error('teeth data is undefined or null.');
    }

});
