# ENPM 818N Midterm

### Prerequisites
1. Make sure you have a keypair on your AWS account, for if you need to ssh into any of the servers.

2. Provide Peter with your AWS Account ID so he can give you access to some resources required to start the stack
    - `DatabaseStartpointSnapshot`: A snapshot of the database required for the project in its initial state. 
    - `WebserverAMI`: An image of the webserver EC2 instance that will be the starting image for all scaling instances. Is pre-configured to serve the PHP website.

### Running the Cloudformation template
1. Make sure you're on `us-east-1` (N. Virginia)
2. Create stack in AWS Console > CloudFormation
    - You can either upload the ecommerce-platform.yaml template file from your local disk, or you can sync it from the Github repository
3. Fill in the required parameters
    - `BastionAMI`: This can usually remain it's default. It's just the first Linux AMI on the free tier from AWS's AMI Catalog
    - `BastionKeyPair`: This should be the name of the keypair on your aws account from prerequisite 1. It will be the method of authentication should you ssh into the system.
    - `DatabasePassword`: Enter any strong password. You shouldn't need to use it unless you want to directly connect to the database for debugging purposes.
    - `DatabaseStartpointSnapshot`: This should remain it's default. It's the snapshot Peter is sharing from his AWS account.
    - `WebserverAMI`: This should remain it's default. It's the AMI image Peter is sharing from his AWS account.
3. Once the stack is up and running, look in the Outputs, and `WebserverURL` should be the location of the running website.

### SSH to the Bastion
#### The bastion host is the entry point into the system, for admin purposes. If you want to SSH into any of the auto-scaling webserver instances, you must SSH there from the bastion.

1. Add your keypair private key to your SSH agent: `ssh-add <path-to-keypair>`
2. SSH to the bastion server with SSH agent forwarding: `ssh -A ec2-user@<bastion-public-ip>`
    - You can get the bastion's public ip address from the Outputs of the CloudFormation stack
    
Once you're on the bastion, you can connect to the database from there, or ssh onto one of the private webserver EC2 instances. 

### SSH to private EC2 instance
1. SSH to the bastion (see above)
    - Make sure to use the `-A` flag as shown above, to use SSH agent forwarding
2. Get the private IP address of the instance you want to go to. You can find it in the AWS Console by looking at the running CloudFormation stack, under resources look for `WebserverAutoScalingGroup`, go to that, then under that find Instance Management, and you can see the running instances. Click on one and copy it's private IP address.
3. From the bastion, `ssh ubuntu@<instance-private-ip>`