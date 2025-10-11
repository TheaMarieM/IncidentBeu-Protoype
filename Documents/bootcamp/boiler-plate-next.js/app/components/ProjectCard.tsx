import React from "react";
import styles from "../portfolio.module.css";

type Project = {
  title: string;
  description: string;
  tech?: string[];
  url?: string;
};

export default function ProjectCard({ project }: { project: Project }) {
  return (
    <article className={styles.card}>
      <div className={styles.cardBody}>
        <h3 className={styles.cardTitle}>{project.title}</h3>
        <p className={styles.cardDescription}>{project.description}</p>
        {project.tech && (
          <p className={styles.cardTech}>
            {project.tech.join(" • ")}
          </p>
        )}
      </div>
      {project.url && (
        <a className={styles.cardLink} href={project.url} target="_blank" rel="noopener noreferrer">
          View
        </a>
      )}
    </article>
  );
}
