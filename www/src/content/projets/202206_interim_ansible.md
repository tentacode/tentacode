---
title: "Automatisation de review apps"
description: "Automatisation de la création d'environnements de test éphémères pour les branches Git, avec Ansible et AWS."
tags:
  [
    { label: "php", lang: "en" },
    { label: "symfony", lang: "en" },
    { label: "ansible", lang: "en" },
    { label: "aws", lang: "en" },
  ]
variant: sm
slug: interim_ansible
publishDate: 2022-06-01
image: ../../assets/projects/ansible_ec2.webp
imageAlt: ""
---

Pour un client dans le secteur de l'intérim, j'ai mis en place une automatisation de la création d'environnements de test éphémères pour les branches Git, avec Ansible et AWS.

La difficulté du projet résidait dans la complexité de l'infrastructure avec de multiples projets sous des stacks différentes (plusieurs versions de PHP par exemple), et le besoin de récupérer une base de données de production anonymsée d'un très gros volume pour chaque environnement de test.

Le projet d'automatisation est constitué d'une petite interface avec un backend et une base de données sous Symfony pour gérer et suivre les déploiements, et monitorer la durée et logger les erreurs. On peut égaler créer une review app via un formulaire en sélectionnant les branches Git et les options de configuration.

Ansible est utilisé pour orchestrer la création de l'infrastructure sur AWS, avec des instances EC2 pour les serveurs d'application et RDS pour les bases de données, en utilisant des snapshots de la base de données.

**Stack :** PHP, Symfony, Ansible, AWS, MySQL, RDS.