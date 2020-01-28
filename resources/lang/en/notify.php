<?php

return [
  'user'              => [
  ],
  'invitation'        => [
    'new'             => [
      'title'         => 'New invitation',
      'message'       => 'You have New Service Invitation from :name on :title'
    ],
    'update'          => [
      'title'         => 'Invitation Attempt',
      'message'       => 'Invitation :action by :name on :title'
    ],
  ],
  'application'       => [
    'new'             => ['title' => 'New Service Application', 'message' => 'new service application from :name'],
    'update'          => [
      'title'         => 'Application Attempt',
      'message'       => 'Application :action by :name on :title'
    ],
  ],
  'service'           => [
    'hired'           => ['title' => 'You are hired', 'message' => 'You have been hired by :name on :title'],
  ],
  'work'              => [
    'started'         => ['title' => 'Job Started', 'message'    => 'The \':title\' job has now started'],
    'completed'       => ['title' => 'Job Compeleted', 'message' => 'The \':title\' job is now completed'],
    'new_review'      => ['title' => 'New Job Review from :name', 'message' => ':name gave you :rating star rating on \':title\' job review']
  ],
  'wallet'            => [
    'updated'         => [
      'sent'          => ['title' => 'Payment Sent',     'message' => 'you :action :currency:amount to :otherName'],
      'received'      => ['title' => 'Payment Received', 'message' => 'you :action :currency:amount from :name'],
    ]
  ],
  'bid'               => [
    'new'             => ['title' => 'New Bid With :model', 'message' => ':name made a new bid from the :model'],
    'update'          => ['title' => 'Bid With :model :action', 'message' => ':name has :action the bid from the :model'],
    // [
    //   'cancel'        => ,
    // ],
  ],
  'payment'           => [
  ],
  'review'            => [
  ],
];
