ENV['VAGRANT_DEFAULT_PROVIDER'] = 'virtualbox'
# Optimized for Vagrant 1.7 and above.
Vagrant.require_version '>= 1.7.0'

Vagrant.configure(2) do |config|
  config.vm.box = 'bento/ubuntu-16.04'
  config.vm.hostname ='ecomm'

  shared_config = {
    private_host_ip: '192.168.50.1',
    private_ip:  '192.168.50.4',
    ssh_port: 22,
    disable_ssh_forward: 'false'
  }

  config.vm.provider :virtualbox do |v|
    v.name = 'ecomm'
    v.customize [
      'modifyvm', :id,
      '--name', 'ecomm',
      '--memory', 512,
      '--natdnshostresolver1', 'on',
      '--cpus', 1
    ]
  end

 
  config.vm.network 'private_network', ip: shared_config[:private_ip]
  #config.vm.network :forwarded_port,
  #                  guest: 22,  host: 2222, host_ip: '127.0.0.1',
  #                  id: 'ssh', disabled: shared_config[:disable_ssh_forward]
  # config.vm.network "forwarded_port", guest: 80,
  # host: 8080, host_ip: "127.0.0.1"

  config.vm.synced_folder './', '/vagrant'

  config.ssh.host = '127.0.0.1' #shared_config[:private_ip]
  config.ssh.port = 2222 #shared_config[:ssh_port]
  # See https://github.com/mitchellh/vagrant/issues/5005
  config.ssh.insert_key = false

  # Run Ansible from the Vagrant VM
  config.vm.provision 'ansible_local' do |ansible|
    ansible.verbose = 'vv'
    ansible.playbook = 'ansible/playbook.yml'
    ansible.extra_vars = {
      vagrant: shared_config
    }
  end

end
