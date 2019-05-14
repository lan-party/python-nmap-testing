#!/usr/bin/python
import nmap
import sys
import requests
import random
import ipwhois
import _thread
import time
import socket
import json
import base64


thread_count = 20


def scan():
    foundone = False

    while foundone == False:
        if len(sys.argv) > 1:
            ip = sys.argv[1]
            foundone = True
        else:
            validated = False
            while validated == False:
                ip = str(random.randint(0, 255)) + "." + str(random.randint(0, 255)) + \
                    "." + str(random.randint(0, 255)) + "." + \
                    str(random.randint(0, 255))
                address = ip.split(".")
                block = address[0] + "." + address[1] + \
                    "." + address[2] + ".0/24"
                try:
                    ipwhois.IPWhois(ip)
                    validated = True
                except Exception:
                    pass
        nm = nmap.PortScanner()
        nm.scan(block, arguments='-p 80,443,23,22,21')
        nm.command_line()
        nm.scaninfo()
        nm.all_hosts()
        results = nm.csv().splitlines()
        hostgrouped = []

        for a in range(0, len(results)):
            resultsplit = results[a].split(';')
            if resultsplit[0] != 'host':
                if resultsplit[0] not in hostgrouped:
                    hostgrouped.append(resultsplit[0])
                    hostgrouped.append([])
                    hostgrouped.append([])
                try:
                    if 'https' in resultsplit[5]:
                        response = requests.get(
                            'https://' + resultsplit[0]).content[:2048]
                        response = base64.encodestring(
                            response).decode('ascii')
                    elif 'http' in resultsplit[5]:
                        response = requests.get(
                            'http://' + resultsplit[0]).content[:2048]
                        response = base64.encodestring(
                            response).decode('ascii')
                    else:
                        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
                            s.connect(resultsplit[0], int(resultsplit[4]))
                            response = s.recv(2048)
                        response = base64.encodestring(
                            response).decode('ascii')
                except Exception:
                    response = ""
                hostgrouped[hostgrouped.index(
                    resultsplit[0]) + 1].append(results[a] + response)

        for a in range(0, len(hostgrouped), 3):
            ipw = ipwhois.IPWhois(hostgrouped[a])
            result = ipw.lookup_rdap()
            hostgrouped[a + 2].append(result)

        if len(hostgrouped) > 0:
            print("")
            for a in range(0, len(hostgrouped), 3):
                rpost = requests.post('http://localhost/run.php', {'ip': str(hostgrouped[a]), 'whois': json.dumps(
                    hostgrouped[a + 2]), 'nmap': json.dumps(hostgrouped[a + 1])})
                print(rpost)
                print(str(hostgrouped[a]))
                print("\n".join(hostgrouped[a + 1]))
                print(str(hostgrouped[a + 2]))
                print("---------------")
            foundone = True


for a in range(0, thread_count):
    time.sleep(1)
    _thread.start_new_thread(scan, ())

while 1:
    pass
