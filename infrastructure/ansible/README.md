# First server setup

```bash
ssh root@server-name.com
```

```bash
useradd -m -d /home/gabriel gabriel
cp -r /root/.ssh /home/gabriel/
chown -R gabriel:gabriel /home/gabriel/.ssh
usermod -s /bin/bash gabriel
visudo
```
> gabriel ALL=(ALL) NOPASSWD: ALL

Test that ansible works:

```bash
cd infrastructure/ansible
ansible all -m ping
```