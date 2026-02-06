import { useState } from "react";
import styles from "./FeedbackTabs.module.css";
import ArrowTopRightOnSquareIcon from "@heroicons/react/24/solid/esm/ArrowTopRightOnSquareIcon";

interface Feedback {
	id: string;
	name: string;
	role: string;
	image: string;
	link: string;
	quote: string;
    context: string;
}

export default function FeedbackTabs({ feedbacks }: { feedbacks: Feedback[] }) {
	const [activeId, setActiveId] = useState(feedbacks[0]?.id);

    const onKeyDownTabs = (event: React.KeyboardEvent<HTMLDivElement>) => {
        const currentIndex = feedbacks.findIndex(fb => fb.id === activeId);
        let newIndex = currentIndex;

        if (event.key === 'ArrowRight') {
            newIndex = (currentIndex + 1) % feedbacks.length;
            setActiveId(feedbacks[newIndex].id);
            document.getElementById(`tab_${feedbacks[newIndex].id}`)?.focus();
            event.preventDefault();
        } else if (event.key === 'ArrowLeft') {
            newIndex = (currentIndex - 1 + feedbacks.length) % feedbacks.length;
            setActiveId(feedbacks[newIndex].id);
            document.getElementById(`tab_${feedbacks[newIndex].id}`)?.focus();    
            event.preventDefault();
        }
    };

	return (
		<>
			<div className={styles.tabs} role="tablist" onKeyDown={onKeyDownTabs}>
				{feedbacks.map((feedback) => (
					<button
                        id={`tab_${feedback.id}`}
						key={feedback.id}
						role="tab"
						aria-selected={activeId === feedback.id}
						aria-controls={`panel_${feedback.id}`}
						onClick={() => setActiveId(feedback.id)}
                        tabIndex={activeId === feedback.id ? 0 : -1}
					>
						<img src={feedback.image} alt={feedback.name} />
					</button>
				))}
			</div>

			{feedbacks.map((feedback) => (
				<div
					key={feedback.id}
					role="tabpanel"
					id={`panel_${feedback.id}`}
                    aria-labelledby={`tab_${feedback.id}`}
					hidden={activeId !== feedback.id}
                    className={styles.feedback}
				>
					<a href={feedback.link} target="_blank" rel="noopener noreferrer" aria-label={`${feedback.name}, ${feedback.role}, nouvelle fenêtre`}>
                        {feedback.name} — {feedback.role}
                        <ArrowTopRightOnSquareIcon className={styles.icon} />
					</a>
                    <p className={styles.context}>Le contexte : {feedback.context}</p>
					<blockquote>{feedback.quote}</blockquote>
				</div>
			))}
		</>
	);
}