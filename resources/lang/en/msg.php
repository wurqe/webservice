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
  'application'       => [
    'pending'         => 'Application already pending',
    'sent'            => 'Application sent successfully',
    'cannot_apply_provide'  => 'You cannot apply for a providing service',
    'only_cancel'     => 'You can only cancel this Application',
    'only_attempt'    => 'You can only accept or reject this Application',
    'updated'         => 'Application updated successfully',
  ],
  'service'           => [
    'not_hired'       => 'You cannot initiate contract that has not been accepted',
    'hired'           => 'Contract initiated successfully',
  ],
  'work'              => [
    'started'         => 'The invitation job has already started',
    'paid'            => 'The work has already been paid for',
    'pay_failed'      => 'The payment failed insuffiecient balance',
    'pays'            => 'The payment was successful',
    'starts'          => 'The service job started successfully',
    'completes'       => 'The service job completed successfully',
    'rated'           => 'The job has already been rated',
  ],
  'payment'           => [
    'responded'       => 'The payment has already been worked on',
  ],
];
