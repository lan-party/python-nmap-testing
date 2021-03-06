#!/usr/bin/python3
import nmap
import sys
import requests
import random
import ipwhois
import _thread
import time
import json
import socket
import base64

scan_thread_count = 10
recheck_thread_count = 0

# SET THESE THEN RUN
recordserver = "";
apitoken = "";

dorks = requests.get(recordserver+'/wordlist.php', {'text': True}).content.decode('ascii').splitlines()
def scan(threadtype, threadid):
	validated = False
	while validated == False:
		if threadtype == 'scan':
			ip = str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))+"."+str(random.randint(0, 255))
			address = ip.split(".")
			block = address[0]+"."+address[1]+"."+address[2]+".0/24"
			try:
				ipwhois.IPWhois(ip)
				validated = True
			except Exception:
				pass
		else:
			block = requests.get(recordserver+'/recheck.php', {'offset': threadid, 'token': apitoken}).content.decode('ascii')
			validated = True
	print("Using ip range: "+block)
	nm = nmap.PortScanner()
	nm.scan(block, arguments='-F')
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
				hostgrouped.append(False)
			if resultsplit[6] == 'open':
				try:
					if 'https' in resultsplit[5]:
						response = requests.get('https://' + resultsplit[0] + ':' + resultsplit[4], verify=False).content.decode('ascii')[:2048]
					elif 'http' in resultsplit[5]:
						response = requests.get('http://' + resultsplit[0] + ':' + resultsplit[4]).content.decode('ascii')[:2048]
					else:
						with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
							s.connect((resultsplit[0], int(resultsplit[4])))
							response = s.recv(2048).decode('ascii')
					for b in range(0, len(dorks)):
						if dorks[b].lower() in response.lower():
							hostgrouped[hostgrouped.index(resultsplit[0])+3] = True
							print("Found string '"+dorks[b].lower()+"' on "+resultsplit[0]+":"+resultsplit[4])
							break
					response = base64.encodebytes(response.encode('ascii')).decode('ascii')
				except Exception as e:
					response = ""
				hostgrouped[hostgrouped.index(resultsplit[0])+1].append(results[a] + response)
	for a in range(0, len(hostgrouped), 4):
		ipw = ipwhois.IPWhois(hostgrouped[a])
		result = ipw.lookup_rdap()
		hostgrouped[a+2].append(result)

	if len(hostgrouped) > 0:
		print("")
		for a in range(0, len(hostgrouped), 4) :
			if hostgrouped[a+3]:
				print("requests.post('"+recordserver+"/run.php', {'token': '"+apitoken+"', 'ip': '"+str(hostgrouped[a])+"', 'whois': '"+json.dumps(hostgrouped[a+2])+"', 'nmap': '"+json.dumps(hostgrouped[a+1])+"'})")
				print("----------")
				requests.post(recordserver+'/run.php', {'token': apitoken, 'ip': str(hostgrouped[a]), 'whois': json.dumps(hostgrouped[a+2]), 'nmap': json.dumps(hostgrouped[a+1])})
		print(block+" done.")
	else:
		print(block+" no active hosts found.\n")
		if threadtype == 'recheck':
			print("requests.get(recordserver+'/prune.php', {'block': block})")
			requests.get(recordserver+'/prune.php', {'token': apitoken, 'block': block})
	_thread.start_new_thread(scan, (threadtype, threadid, ))


for a in range(0, scan_thread_count):
	time.sleep(1) # Assuming the numbers won't be as random otherwise
	_thread.start_new_thread(scan, ('scan', a, ))

for a in range(0, recheck_thread_count):
	time.sleep(1)
	_thread.start_new_thread(scan, ('recheck', a, ))

while 1:
	pass
