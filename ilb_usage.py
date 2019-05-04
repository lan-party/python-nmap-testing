#!/usr/bin/python
import nmap

nm = nmap.PortScanner()
nm.scan('127.0.0.1', '1-255')
nm.command_line()
nm.scaninfo()
nm.all_hosts()
print(nm['127.0.0.1'].hostname())
print(nm['127.0.0.1'].hostnames())
print(nm['127.0.0.1'].all_protocols())

