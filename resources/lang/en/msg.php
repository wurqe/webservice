<?php

return [
  'user'              => [
    'login'           => 'Login Successful',
    'not_exist'       => "We can't find a user with the provided credentials."
  ],
  'invitation'        => [
    'pending'         => 'Invitation already pending',
    'sent'            => 'Invitation sent successfully',
    'only_cancel'     => 'You can only cancel this Invitation',
    'only_attempt'    => 'You can only accept or reject this Invitation',
    'updated'         => 'Invitation updated successfully',
  ],
  'service'           => [
    'not_hired'       => 'You cannot initiate contract that has not been accepted',
    'hired'           => 'Contract initiated successfully',
  ],
  'work'              => [
    'started'         => 'The invitation job has already started',
    'starts'          => 'The service job started successfully',
    'completes'       => 'The service job completed successfully',
    'rated'           => 'The job has already been rated',
  ],
  'payment'           => [
    'responded'         => 'The payment has already been worked on',
  ],
];
